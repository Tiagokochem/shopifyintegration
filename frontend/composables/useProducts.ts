import { useQuery } from '@vue/apollo-composable';

export interface Product {
  id: string;
  shopify_id: string;
  title: string;
  description?: string;
  price: number;
  vendor?: string;
  product_type?: string;
  status: string;
  synced_at?: string;
  created_at: string;
  updated_at: string;
}

export interface ProductsQueryVariables {
  first?: number;
  page?: number;
  search?: string;
  vendor?: string;
  product_type?: string;
}

export interface PaginatorInfo {
  count: number;
  currentPage: number;
  firstItem?: number;
  hasMorePages: boolean;
  lastItem?: number;
  lastPage: number;
  perPage: number;
  total: number;
}

export interface ProductsResponse {
  products: {
    data: Product[];
    paginatorInfo: PaginatorInfo;
  };
}

const PRODUCTS_QUERY = `
  query Products(
    $first: Int
    $page: Int
    $search: String
    $vendor: String
    $product_type: String
  ) {
    products(
      first: $first
      page: $page
      search: $search
      vendor: $vendor
      product_type: $product_type
    ) {
      data {
        id
        shopify_id
        title
        description
        price
        vendor
        product_type
        status
        synced_at
        created_at
        updated_at
      }
      paginatorInfo {
        count
        currentPage
        firstItem
        hasMorePages
        lastItem
        lastPage
        perPage
        total
      }
    }
  }
`;

export const useProducts = (variables: ProductsQueryVariables = {}) => {
  return useQuery<ProductsResponse, ProductsQueryVariables>(
    PRODUCTS_QUERY,
    variables,
    {
      fetchPolicy: 'cache-and-network',
    }
  );
};
