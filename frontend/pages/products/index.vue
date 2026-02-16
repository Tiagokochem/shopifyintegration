<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
          <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Products</h1>
            <p class="text-gray-600" v-if="paginatorInfo">
              {{ paginatorInfo.total }} {{ paginatorInfo.total === 1 ? 'product' : 'products' }} total
            </p>
          </div>
          <button
            @click="openCreateModal"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 transform hover:scale-105"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Product
          </button>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6 border border-gray-200">
          <div class="flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <h2 class="text-lg font-semibold text-gray-900">Filters</h2>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Search Products
              </label>
              <div class="relative">
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Search by title or description..."
                  class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                  @input="debouncedSearch"
                />
                <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Vendor
              </label>
              <input
                v-model="filters.vendor"
                type="text"
                placeholder="Filter by vendor..."
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
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
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                @input="debouncedSearch"
              />
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex flex-col items-center justify-center py-20">
          <div class="relative">
            <div class="w-16 h-16 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin"></div>
          </div>
          <p class="mt-6 text-gray-600 font-medium">Loading products...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6 mb-6 shadow-md">
          <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
              <h3 class="text-red-800 font-semibold">Error loading products</h3>
              <p class="text-red-600 mt-1">{{ error.message }}</p>
            </div>
          </div>
        </div>

        <!-- Products Grid -->
        <div v-else-if="products && products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="product in products"
            :key="product.id"
            class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-200 overflow-hidden group"
          >
            <!-- Card Header -->
            <div class="p-6 pb-4">
              <div class="flex items-start justify-between mb-3">
                <div class="flex-1 min-w-0">
                  <h3 class="text-xl font-bold text-gray-900 truncate group-hover:text-blue-600 transition-colors">
                    {{ product.title }}
                  </h3>
                </div>
                <div class="ml-3 flex-shrink-0">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                    :class="{
                      'bg-green-100 text-green-800': product.status === 'active',
                      'bg-yellow-100 text-yellow-800': product.status === 'draft',
                      'bg-red-100 text-red-800': product.status === 'archived',
                    }"
                  >
                    {{ product.status }}
                  </span>
                </div>
              </div>

              <!-- Price -->
              <div class="mb-4">
                <span class="text-3xl font-bold text-blue-600">${{ formatPrice(product.price) }}</span>
              </div>

              <!-- Description -->
              <p v-if="product.description" class="text-gray-600 text-sm mb-4 line-clamp-2">
                {{ product.description }}
              </p>

              <!-- Metadata -->
              <div class="space-y-2 mb-4">
                <div v-if="product.vendor" class="flex items-center gap-2 text-sm text-gray-600">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  <span>{{ product.vendor }}</span>
                </div>
                <div v-if="product.product_type" class="flex items-center gap-2 text-sm text-gray-600">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                  </svg>
                  <span>{{ product.product_type }}</span>
                </div>
                <div v-if="product.shopify_id" class="flex items-center gap-2 text-xs text-gray-500">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                  </svg>
                  <span class="truncate">Shopify: {{ product.shopify_id }}</span>
                </div>
                <div v-if="product.synced_at" class="flex items-center gap-2 text-xs text-gray-500">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span>Synced: {{ formatDate(product.synced_at) }}</span>
                </div>
              </div>

              <!-- Auto Sync Toggle -->
              <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                <label class="flex items-center justify-between cursor-pointer">
                  <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Auto Sync</span>
                  </div>
                  <input
                    type="checkbox"
                    :checked="product.sync_auto"
                    @change="toggleSyncAuto(product.id, ($event.target as HTMLInputElement).checked)"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                  />
                </label>
              </div>
            </div>

            <!-- Card Footer - Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex gap-2">
              <button
                @click="openEditModal(product)"
                class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400 transition-all"
                title="Edit product"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
              </button>
              <button
                @click="syncToShopify(product.id)"
                :disabled="syncingProducts.includes(product.id)"
                class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 hover:border-blue-300 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                title="Sync to Shopify"
              >
                <svg v-if="!syncingProducts.includes(product.id)" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <div v-else class="w-4 h-4 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                {{ syncingProducts.includes(product.id) ? 'Syncing...' : 'Sync' }}
              </button>
              <button
                @click="confirmDelete(product)"
                class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 hover:border-red-300 transition-all"
                title="Delete product"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="paginatorInfo && products.length > 0" class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white px-6 py-4 rounded-xl shadow-md border border-gray-200">
          <div class="text-sm text-gray-700">
            Showing <span class="font-semibold">{{ paginatorInfo.firstItem }}</span> to
            <span class="font-semibold">{{ paginatorInfo.lastItem }}</span> of
            <span class="font-semibold">{{ paginatorInfo.total }}</span> results
          </div>
          <div class="flex gap-2">
            <button
              :disabled="paginatorInfo.currentPage === 1"
              @click="goToPage(paginatorInfo.currentPage - 1)"
              class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
              Previous
            </button>
            <div class="px-4 py-2 text-sm font-medium text-gray-700">
              Page {{ paginatorInfo.currentPage }} of {{ paginatorInfo.lastPage }}
            </div>
            <button
              :disabled="!paginatorInfo.hasMorePages"
              @click="goToPage(paginatorInfo.currentPage + 1)"
              class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              Next
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-20 bg-white rounded-xl shadow-md border border-gray-200">
          <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
          <h3 class="text-xl font-semibold text-gray-900 mb-2">No products found</h3>
          <p class="text-gray-600 mb-6">Try adjusting your filters or create a new product</p>
          <button
            @click="openCreateModal"
            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Your First Product
          </button>
        </div>
      </div>
    </div>

    <!-- Product Form Modal -->
    <Transition name="modal">
      <div
        v-if="showModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="closeModal"
      >
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
          <!-- Modal Header -->
          <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between">
              <h2 class="text-2xl font-bold text-gray-900">
                {{ editingProduct ? 'Edit Product' : 'Create New Product' }}
              </h2>
              <button
                @click="closeModal"
                class="text-gray-400 hover:text-gray-600 transition-colors"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Modal Body -->
          <div class="px-6 py-6 overflow-y-auto flex-1">
            <form @submit.prevent="saveProduct" class="space-y-6">
              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Title <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="formData.title"
                  type="text"
                  required
                  placeholder="Enter product title..."
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
              </div>

              <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                  Description
                </label>
                <textarea
                  v-model="formData.description"
                  rows="4"
                  placeholder="Enter product description..."
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                ></textarea>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Price <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-500">$</span>
                    <input
                      v-model.number="formData.price"
                      type="number"
                      step="0.01"
                      min="0"
                      required
                      placeholder="0.00"
                      class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    />
                  </div>
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Status
                  </label>
                  <select
                    v-model="formData.status"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                  >
                    <option value="active">Active</option>
                    <option value="draft">Draft</option>
                    <option value="archived">Archived</option>
                  </select>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Vendor
                  </label>
                  <input
                    v-model="formData.vendor"
                    type="text"
                    placeholder="Enter vendor name..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                  />
                </div>

                <div>
                  <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Product Type
                  </label>
                  <input
                    v-model="formData.product_type"
                    type="text"
                    placeholder="Enter product type..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                  />
                </div>
              </div>

              <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                <label class="flex items-center gap-3 cursor-pointer">
                  <input
                    v-model="formData.sync_auto"
                    type="checkbox"
                    class="w-5 h-5 text-blue-600 bg-white border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                  />
                  <div>
                    <span class="text-sm font-semibold text-gray-900">Automatically sync to Shopify</span>
                    <p class="text-xs text-gray-600 mt-1">Changes will be automatically synced to your Shopify store</p>
                  </div>
                </label>
              </div>

              <!-- Modal Footer -->
              <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <button
                  type="button"
                  @click="closeModal"
                  class="px-6 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="saving"
                  class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
                >
                  <div v-if="saving" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                  {{ saving ? 'Saving...' : (editingProduct ? 'Update Product' : 'Create Product') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </Transition>
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

<style scoped>
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

.modal-enter-active > div,
.modal-leave-active > div {
  transition: transform 0.3s ease;
}

.modal-enter-from > div,
.modal-leave-to > div {
  transform: scale(0.9);
}
</style>
