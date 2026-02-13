import { DefaultApolloClient } from '@vue/apollo-composable';
import { ApolloClient, InMemoryCache, createHttpLink } from '@apollo/client/core';
import { setContext } from '@apollo/client/link/context';

export default defineNuxtPlugin((nuxtApp) => {
  const config = useRuntimeConfig();
  const graphqlEndpoint = config.public.graphqlEndpoint || 'http://localhost:8082/graphql';

  const httpLink = createHttpLink({
    uri: graphqlEndpoint,
  });

  const authLink = setContext((_, { headers }) => {
    return {
      headers: {
        ...headers,
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
    };
  });

  const apolloClient = new ApolloClient({
    link: authLink.concat(httpLink),
    cache: new InMemoryCache(),
    defaultOptions: {
      watchQuery: {
        fetchPolicy: 'cache-and-network',
      },
    },
  });

  nuxtApp.vueApp.provide(DefaultApolloClient, apolloClient);
});
