<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Products</h1>

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
                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                  {{ product.title }}
                </h2>
                <p v-if="product.description" class="text-gray-600 mb-4 line-clamp-2">
                  {{ product.description }}
                </p>
                <div class="flex flex-wrap gap-4 text-sm text-gray-500">
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
                </div>
              </div>
              <div class="ml-6 text-right">
                <div class="text-2xl font-bold text-blue-600">
                  ${{ formatPrice(product.price) }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                  Shopify ID: {{ product.shopify_id }}
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
          <p class="text-gray-500 mt-2">Try adjusting your filters</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { useProducts, type Product, type PaginatorInfo } from '~/composables/useProducts';

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

watch(filters, () => {
  debouncedSearch();
}, { deep: true });
</script>
