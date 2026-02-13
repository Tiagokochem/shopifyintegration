# GraphQL Query Examples

## Query Products

```graphql
query {
  products(first: 10) {
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
```

## Query Products with Search

```graphql
query {
  products(first: 10, search: "iPhone") {
    data {
      id
      title
      price
    }
    paginatorInfo {
      total
    }
  }
}
```

## Query Products with Filters

```graphql
query {
  products(
    first: 10
    vendor: "Apple"
    product_type: "Electronics"
  ) {
    data {
      id
      title
      vendor
      product_type
      price
    }
    paginatorInfo {
      total
    }
  }
}
```

## Query Single Product

```graphql
query {
  product(id: "1") {
    id
    shopify_id
    title
    description
    price
    vendor
    product_type
    status
  }
}
```

## Using cURL

```bash
curl -X POST http://localhost:8082/graphql \
  -H "Content-Type: application/json" \
  -d '{
    "query": "query { products(first: 10) { data { id title price } paginatorInfo { total } } }"
  }'
```
