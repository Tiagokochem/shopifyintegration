<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold text-gray-900">Products</h1>
          <button
            @click="openCreateModal"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
          >
            + New Product
          </button>
        </div>

        <!-- Filters -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Search
              </label>
              <input
                v-model="filters.search"
                type="text"
                placeholder="Search products..."
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                @input="debouncedSearch"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Vendor
              </label>
              <input
                v-model="filters.vendor"
                type="text"
                placeholder="Filter by vendor..."
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                @input="debouncedSearch"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Product Type
              </label>
              <input
                v-model="filters.product_type"
                type="text"
                placeholder="Filter by type..."
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                @input="debouncedSearch"
              />
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="text-center py-12">
          <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
          <p class="mt-4 text-gray-600">Loading products...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
          <p class="text-red-800">Error loading products: {{ error.message }}</p>
        </div>

        <!-- Products List -->
        <div v-else-if="products && products.length > 0" class="space-y-4">
          <div
            v-for="product in products"
            :key="product.id"
            class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow"
          >
            <div class="flex justify-between items-start">
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                  <h2 class="text-xl font-semibold text-gray-900">
                    {{ product.title }}
                  </h2>
                  <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input
                      type="checkbox"
                      :checked="product.sync_auto"
                      @change="toggleSyncAuto(product.id, ($event.target as HTMLInputElement).checked)"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span>Auto Sync</span>
                  </label>
                </div>
                <p v-if="product.description" class="text-gray-600 mb-4 line-clamp-2">
                  {{ product.description }}
                </p>
                <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-4">
                  <span v-if="product.vendor">
                    <strong>Vendor:</strong> {{ product.vendor }}
                  </span>
                  <span v-if="product.product_type">
                    <strong>Type:</strong> {{ product.product_type }}
                  </span>
                  <span>
                    <strong>Status:</strong>
                    <span
                      :class="{
                        'text-green-600': product.status === 'active',
                        'text-yellow-600': product.status === 'draft',
                        'text-red-600': product.status === 'archived',
                      }"
                    >
                      {{ product.status }}
                    </span>
                  </span>
                  <span v-if="product.shopify_id" class="text-xs">
                    <strong>Shopify ID:</strong> {{ product.shopify_id }}
                  </span>
                  <span v-if="product.synced_at" class="text-xs">
                    <strong>Synced:</strong> {{ formatDate(product.synced_at) }}
                  </span>
                </div>
                <div class="flex gap-2">
                  <button
                    @click="openEditModal(product)"
                    class="px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors"
                  >
                    Edit
                  </button>
                  <button
                    @click="syncToShopify(product.id)"
                    :disabled="syncingProducts.includes(product.id)"
                    class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors disabled:opacity-50"
                  >
                    {{ syncingProducts.includes(product.id) ? 'Syncing...' : 'Sync to Shopify' }}
                  </button>
                  <button
                    @click="confirmDelete(product)"
                    class="px-3 py-1 text-sm bg-red-100 text-red-700 rounded hover:bg-red-200 transition-colors"
                  >
                    Delete
                  </button>
                </div>
              </div>
              <div class="ml-6 text-right">
                <div class="text-2xl font-bold text-blue-600">
                  ${{ formatPrice(product.price) }}
                </div>
              </div>
            </div>
          </div>

          <!-- Pagination -->
          <div v-if="paginatorInfo" class="mt-8 flex items-center justify-between bg-white px-6 py-4 rounded-lg shadow">
            <div class="text-sm text-gray-700">
              Showing {{ paginatorInfo.firstItem }} to {{ paginatorInfo.lastItem }} of
              {{ paginatorInfo.total }} results
            </div>
            <div class="flex gap-2">
              <button
                :disabled="paginatorInfo.currentPage === 1"
                @click="goToPage(paginatorInfo.currentPage - 1)"
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Previous
              </button>
              <button
                :disabled="!paginatorInfo.hasMorePages"
                @click="goToPage(paginatorInfo.currentPage + 1)"
                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                Next
              </button>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12 bg-white rounded-lg shadow">
          <p class="text-gray-600 text-lg">No products found</p>
          <p class="text-gray-500 mt-2">Try adjusting your filters or create a new product</p>
        </div>
      </div>
    </div>

    <!-- Product Form Modal -->
    <div
      v-if="showModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click.self="closeModal"
    >
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <h2 class="text-2xl font-bold text-gray-900 mb-6">
            {{ editingProduct ? 'Edit Product' : 'Create Product' }}
          </h2>

          <form @submit.prevent="saveProduct" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Title <span class="text-red-500">*</span>
              </label>
              <input
                v-model="formData.title"
                type="text"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Description
              </label>
              <textarea
                v-model="formData.description"
                rows="4"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
              ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Price <span class="text-red-500">*</span>
                </label>
                <input
                  v-model.number="formData.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Status
                </label>
                <select
                  v-model="formData.status"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                >
                  <option value="active">Active</option>
                  <option value="draft">Draft</option>
                  <option value="archived">Archived</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Vendor
                </label>
                <input
                  v-model="formData.vendor"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Product Type
                </label>
                <input
                  v-model="formData.product_type"
                  type="text"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                />
              </div>
            </div>

            <div>
              <label class="flex items-center gap-2">
                <input
                  v-model="formData.sync_auto"
                  type="checkbox"
                  class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                />
                <span class="text-sm font-medium text-gray-700">
                  Automatically sync to Shopify
                </span>
              </label>
            </div>

            <div class="flex justify-end gap-3 pt-4">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors"
              >
                Cancel
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50"
              >
                {{ saving ? 'Saving...' : (editingProduct ? 'Update' : 'Create') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { useProducts, type Product, type PaginatorInfo } from '~/composables/useProducts';
import {
  useCreateProduct,
  useUpdateProduct,
  useDeleteProduct,
  useSyncProductToShopify,
  useToggleSyncAuto,
  type CreateProductInput,
  type UpdateProductInput,
} from '~/composables/useProductMutations';

const filters = ref({
  search: '',
  vendor: '',
  product_type: '',
});

const currentPage = ref(1);
let searchTimeout: ReturnType<typeof setTimeout> | null = null;

const { result, loading, error, refetch } = useProducts({
  first: 10,
  page: currentPage.value,
  ...filters.value,
});

const products = computed<Product[]>(() => {
  return result.value?.products?.data || [];
});

const paginatorInfo = computed<PaginatorInfo | null>(() => {
  return result.value?.products?.paginatorInfo || null;
});

// Modal state
const showModal = ref(false);
const editingProduct = ref<Product | null>(null);
const saving = ref(false);
const syncingProducts = ref<string[]>([]);

// Form data
const formData = ref<CreateProductInput>({
  title: '',
  description: '',
  price: 0,
  vendor: '',
  product_type: '',
  status: 'active',
  sync_auto: false,
});

// Mutations
const { mutate: createProduct } = useCreateProduct();
const { mutate: updateProduct } = useUpdateProduct();
const { mutate: deleteProduct } = useDeleteProduct();
const { mutate: syncToShopifyMutation } = useSyncProductToShopify();
const { mutate: toggleSyncAutoMutation } = useToggleSyncAuto();

const openCreateModal = () => {
  editingProduct.value = null;
  formData.value = {
    title: '',
    description: '',
    price: 0,
    vendor: '',
    product_type: '',
    status: 'active',
    sync_auto: false,
  };
  showModal.value = true;
};

const openEditModal = (product: Product) => {
  editingProduct.value = product;
  formData.value = {
    title: product.title,
    description: product.description || '',
    price: product.price,
    vendor: product.vendor || '',
    product_type: product.product_type || '',
    status: product.status,
    sync_auto: product.sync_auto || false,
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  editingProduct.value = null;
};

const saveProduct = async () => {
  saving.value = true;
  try {
    if (editingProduct.value) {
      await updateProduct({
        id: editingProduct.value.id,
        ...formData.value,
      } as UpdateProductInput);
    } else {
      await createProduct(formData.value);
    }
    closeModal();
    await refetch();
  } catch (err: any) {
    alert('Error saving product: ' + (err.message || 'Unknown error'));
  } finally {
    saving.value = false;
  }
};

const confirmDelete = async (product: Product) => {
  if (!confirm(`Are you sure you want to delete "${product.title}"?`)) {
    return;
  }

  try {
    await deleteProduct({ id: product.id });
    await refetch();
  } catch (err: any) {
    alert('Error deleting product: ' + (err.message || 'Unknown error'));
  }
};

const syncToShopify = async (productId: string) => {
  syncingProducts.value.push(productId);
  try {
    await syncToShopifyMutation({ id: productId });
    await refetch();
  } catch (err: any) {
    alert('Error syncing product: ' + (err.message || 'Unknown error'));
  } finally {
    syncingProducts.value = syncingProducts.value.filter(id => id !== productId);
  }
};

const toggleSyncAuto = async (productId: string, syncAuto: boolean) => {
  try {
    await toggleSyncAutoMutation({ id: productId, sync_auto: syncAuto });
    await refetch();
  } catch (err: any) {
    alert('Error updating sync setting: ' + (err.message || 'Unknown error'));
    await refetch(); // Revert UI state
  }
};

const debouncedSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
  searchTimeout = setTimeout(() => {
    currentPage.value = 1;
    refetch({
      first: 10,
      page: 1,
      ...filters.value,
    });
  }, 500);
};

const goToPage = (page: number) => {
  currentPage.value = page;
  refetch({
    first: 10,
    page,
    ...filters.value,
  });
};

const formatPrice = (price: number): string => {
  return price.toFixed(2);
};

const formatDate = (dateString: string): string => {
  return new Date(dateString).toLocaleString();
};

watch(filters, () => {
  debouncedSearch();
}, { deep: true });
</script>
