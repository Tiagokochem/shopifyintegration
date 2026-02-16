import { useQuery } from '@vue/apollo-composable';
import gql from 'graphql-tag';

export interface Product {
  id: string;
  shopify_id?: string;
  handle?: string;
  title: string;
  description?: string;
  price: number;
  compare_at_price?: number;
  vendor?: string;
  product_type?: string;
  tags?: string;
  status: string;
  sku?: string;
  weight?: number;
  weight_unit?: string;
  requires_shipping?: boolean;
  tracked?: boolean;
  inventory_quantity?: number;
  meta_title?: string;
  meta_description?: string;
  featured_image?: string;
  template_suffix?: string;
  published?: boolean;
  published_at?: string;
  sync_auto?: boolean;
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

const PRODUCTS_QUERY = gql`
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
        handle
        title
        description
        price
        compare_at_price
        vendor
        product_type
        tags
        status
        sku
        weight
        weight_unit
        requires_shipping
        tracked
        inventory_quantity
        meta_title
        meta_description
        featured_image
        template_suffix
        published
        published_at
        sync_auto
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
