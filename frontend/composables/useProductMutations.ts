import { useMutation } from '@vue/apollo-composable';
import gql from 'graphql-tag';

export interface CreateProductInput {
  title: string;
  handle?: string;
  description?: string;
  price: number;
  compare_at_price?: number;
  vendor?: string;
  product_type?: string;
  tags?: string;
  status?: string;
  sku?: string;
  weight?: number;
  weight_unit?: string;
  requires_shipping?: boolean;
  tracked?: boolean;
  inventory_quantity?: number;
  meta_title?: string;
  meta_description?: string;
  template_suffix?: string;
  published?: boolean;
  sync_auto?: boolean;
}

export interface UpdateProductInput {
  id: string;
  title?: string;
  handle?: string;
  description?: string;
  price?: number;
  compare_at_price?: number;
  vendor?: string;
  product_type?: string;
  tags?: string;
  status?: string;
  sku?: string;
  weight?: number;
  weight_unit?: string;
  requires_shipping?: boolean;
  tracked?: boolean;
  inventory_quantity?: number;
  meta_title?: string;
  meta_description?: string;
  template_suffix?: string;
  published?: boolean;
  sync_auto?: boolean;
}

const CREATE_PRODUCT_MUTATION = gql`
  mutation CreateProduct(
    $title: String!
    $handle: String
    $description: String
    $price: Float!
    $compare_at_price: Float
    $vendor: String
    $product_type: String
    $tags: String
    $status: String
    $sku: String
    $weight: Float
    $weight_unit: String
    $requires_shipping: Boolean
    $tracked: Boolean
    $inventory_quantity: Int
    $meta_title: String
    $meta_description: String
    $template_suffix: String
    $published: Boolean
    $sync_auto: Boolean
  ) {
    createProduct(
      title: $title
      handle: $handle
      description: $description
      price: $price
      compare_at_price: $compare_at_price
      vendor: $vendor
      product_type: $product_type
      tags: $tags
      status: $status
      sku: $sku
      weight: $weight
      weight_unit: $weight_unit
      requires_shipping: $requires_shipping
      tracked: $tracked
      inventory_quantity: $inventory_quantity
      meta_title: $meta_title
      meta_description: $meta_description
      template_suffix: $template_suffix
      published: $published
      sync_auto: $sync_auto
    ) {
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
  }
`;

const UPDATE_PRODUCT_MUTATION = gql`
  mutation UpdateProduct(
    $id: ID!
    $title: String
    $handle: String
    $description: String
    $price: Float
    $compare_at_price: Float
    $vendor: String
    $product_type: String
    $tags: String
    $status: String
    $sku: String
    $weight: Float
    $weight_unit: String
    $requires_shipping: Boolean
    $tracked: Boolean
    $inventory_quantity: Int
    $meta_title: String
    $meta_description: String
    $template_suffix: String
    $published: Boolean
    $sync_auto: Boolean
  ) {
    updateProduct(
      id: $id
      title: $title
      handle: $handle
      description: $description
      price: $price
      compare_at_price: $compare_at_price
      vendor: $vendor
      product_type: $product_type
      tags: $tags
      status: $status
      sku: $sku
      weight: $weight
      weight_unit: $weight_unit
      requires_shipping: $requires_shipping
      tracked: $tracked
      inventory_quantity: $inventory_quantity
      meta_title: $meta_title
      meta_description: $meta_description
      template_suffix: $template_suffix
      published: $published
      sync_auto: $sync_auto
    ) {
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
  }
`;

const DELETE_PRODUCT_MUTATION = gql`
  mutation DeleteProduct($id: ID!) {
    deleteProduct(id: $id)
  }
`;

const SYNC_PRODUCT_TO_SHOPIFY_MUTATION = gql`
  mutation SyncProductToShopify($id: ID!) {
    syncProductToShopify(id: $id) {
      id
      shopify_id
      synced_at
    }
  }
`;

const TOGGLE_SYNC_AUTO_MUTATION = gql`
  mutation ToggleProductSyncAuto($id: ID!, $sync_auto: Boolean!) {
    toggleProductSyncAuto(id: $id, sync_auto: $sync_auto) {
      id
      sync_auto
    }
  }
`;

const SYNC_PRODUCTS_FROM_SHOPIFY_MUTATION = gql`
  mutation SyncProductsFromShopify($limit: Int) {
    syncProductsFromShopify(limit: $limit) {
      success
      message
      stats {
        created
        updated
        skipped
        errors
      }
    }
  }
`;

export const useCreateProduct = () => {
  return useMutation(CREATE_PRODUCT_MUTATION);
};

export const useUpdateProduct = () => {
  return useMutation(UPDATE_PRODUCT_MUTATION);
};

export const useDeleteProduct = () => {
  return useMutation(DELETE_PRODUCT_MUTATION);
};

export const useSyncProductToShopify = () => {
  return useMutation(SYNC_PRODUCT_TO_SHOPIFY_MUTATION);
};

export const useToggleSyncAuto = () => {
  return useMutation(TOGGLE_SYNC_AUTO_MUTATION);
};

export interface SyncProductsFromShopifyResult {
  syncProductsFromShopify: {
    success: boolean;
    message: string;
    stats: {
      created: number;
      updated: number;
      skipped: number;
      errors: number;
    };
  };
}

export const useSyncProductsFromShopify = () => {
  return useMutation<SyncProductsFromShopifyResult, { limit?: number }>(
    SYNC_PRODUCTS_FROM_SHOPIFY_MUTATION
  );
};
