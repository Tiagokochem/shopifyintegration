import { useMutation } from '@vue/apollo-composable';
import gql from 'graphql-tag';

export interface CreateProductInput {
  title: string;
  description?: string;
  price: number;
  vendor?: string;
  product_type?: string;
  status?: string;
  sync_auto?: boolean;
}

export interface UpdateProductInput {
  id: string;
  title?: string;
  description?: string;
  price?: number;
  vendor?: string;
  product_type?: string;
  status?: string;
  sync_auto?: boolean;
}

const CREATE_PRODUCT_MUTATION = gql`
  mutation CreateProduct(
    $title: String!
    $description: String
    $price: Float!
    $vendor: String
    $product_type: String
    $status: String
    $sync_auto: Boolean
  ) {
    createProduct(
      title: $title
      description: $description
      price: $price
      vendor: $vendor
      product_type: $product_type
      status: $status
      sync_auto: $sync_auto
    ) {
      id
      shopify_id
      title
      description
      price
      vendor
      product_type
      status
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
    $description: String
    $price: Float
    $vendor: String
    $product_type: String
    $status: String
    $sync_auto: Boolean
  ) {
    updateProduct(
      id: $id
      title: $title
      description: $description
      price: $price
      vendor: $vendor
      product_type: $product_type
      status: $status
      sync_auto: $sync_auto
    ) {
      id
      shopify_id
      title
      description
      price
      vendor
      product_type
      status
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
