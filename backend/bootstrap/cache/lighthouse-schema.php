<?php return array (
  'types' => 
  array (
    'Query' => 
    array (
      'kind' => 'ObjectTypeDefinition',
      'name' => 
      array (
        'kind' => 'Name',
        'value' => 'Query',
      ),
      'interfaces' => 
      array (
      ),
      'directives' => 
      array (
      ),
      'fields' => 
      array (
        0 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'products',
          ),
          'arguments' => 
          array (
            0 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'first',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Int',
                ),
              ),
              'defaultValue' => 
              array (
                'kind' => 'IntValue',
                'value' => '10',
              ),
              'directives' => 
              array (
              ),
            ),
            1 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'page',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Int',
                ),
              ),
              'defaultValue' => 
              array (
                'kind' => 'IntValue',
                'value' => '1',
              ),
              'directives' => 
              array (
              ),
            ),
            2 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'search',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            3 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'vendor',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            4 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'product_type',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'ProductPaginator',
            ),
          ),
          'directives' => 
          array (
            0 => 
            array (
              'kind' => 'Directive',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'field',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'kind' => 'StringValue',
                    'value' => 'App\\GraphQL\\Queries\\ProductsQuery',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'resolver',
                  ),
                ),
              ),
            ),
          ),
        ),
        1 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'product',
          ),
          'arguments' => 
          array (
            0 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'id',
              ),
              'type' => 
              array (
                'kind' => 'NonNullType',
                'type' => 
                array (
                  'kind' => 'NamedType',
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'ID',
                  ),
                ),
              ),
              'directives' => 
              array (
                0 => 
                array (
                  'kind' => 'Directive',
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'eq',
                  ),
                  'arguments' => 
                  array (
                  ),
                ),
              ),
            ),
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Product',
            ),
          ),
          'directives' => 
          array (
            0 => 
            array (
              'kind' => 'Directive',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'find',
              ),
              'arguments' => 
              array (
              ),
            ),
          ),
        ),
      ),
    ),
    'Mutation' => 
    array (
      'kind' => 'ObjectTypeDefinition',
      'name' => 
      array (
        'kind' => 'Name',
        'value' => 'Mutation',
      ),
      'interfaces' => 
      array (
      ),
      'directives' => 
      array (
      ),
      'fields' => 
      array (
        0 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'createProduct',
          ),
          'arguments' => 
          array (
            0 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'title',
              ),
              'type' => 
              array (
                'kind' => 'NonNullType',
                'type' => 
                array (
                  'kind' => 'NamedType',
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'String',
                  ),
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            1 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'handle',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            2 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'description',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            3 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'price',
              ),
              'type' => 
              array (
                'kind' => 'NonNullType',
                'type' => 
                array (
                  'kind' => 'NamedType',
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'Float',
                  ),
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            4 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'compare_at_price',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Float',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            5 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'vendor',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            6 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'product_type',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            7 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'tags',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            8 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'status',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            9 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'sku',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            10 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'weight',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Float',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            11 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'weight_unit',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            12 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'requires_shipping',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Boolean',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            13 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'tracked',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Boolean',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            14 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'inventory_quantity',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Int',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            15 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'meta_title',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            16 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'meta_description',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            17 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'template_suffix',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            18 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'published',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Boolean',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            19 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'sync_auto',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Boolean',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Product',
            ),
          ),
          'directives' => 
          array (
            0 => 
            array (
              'kind' => 'Directive',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'field',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'kind' => 'StringValue',
                    'value' => 'App\\GraphQL\\Mutations\\CreateProductMutation',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'resolver',
                  ),
                ),
              ),
            ),
          ),
        ),
        1 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'updateProduct',
          ),
          'arguments' => 
          array (
            0 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'id',
              ),
              'type' => 
              array (
                'kind' => 'NonNullType',
                'type' => 
                array (
                  'kind' => 'NamedType',
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'ID',
                  ),
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            1 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'title',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            2 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'handle',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            3 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'description',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            4 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'price',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Float',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            5 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'compare_at_price',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Float',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            6 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'vendor',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            7 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'product_type',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            8 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'tags',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            9 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'status',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            10 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'sku',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            11 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'weight',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Float',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            12 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'weight_unit',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            13 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'requires_shipping',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Boolean',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            14 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'tracked',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Boolean',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            15 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'inventory_quantity',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Int',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            16 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'meta_title',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            17 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'meta_description',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            18 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'template_suffix',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'String',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            19 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'published',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Boolean',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            20 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'sync_auto',
              ),
              'type' => 
              array (
                'kind' => 'NamedType',
                'name' => 
                array (
                  'kind' => 'Name',
                  'value' => 'Boolean',
                ),
              ),
              'directives' => 
              array (
              ),
            ),
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Product',
            ),
          ),
          'directives' => 
          array (
            0 => 
            array (
              'kind' => 'Directive',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'field',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'kind' => 'StringValue',
                    'value' => 'App\\GraphQL\\Mutations\\UpdateProductMutation',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'resolver',
                  ),
                ),
              ),
            ),
          ),
        ),
        2 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'deleteProduct',
          ),
          'arguments' => 
          array (
            0 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'id',
              ),
              'type' => 
              array (
                'kind' => 'NonNullType',
                'type' => 
                array (
                  'kind' => 'NamedType',
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'ID',
                  ),
                ),
              ),
              'directives' => 
              array (
              ),
            ),
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'Boolean',
              ),
            ),
          ),
          'directives' => 
          array (
            0 => 
            array (
              'kind' => 'Directive',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'field',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'kind' => 'StringValue',
                    'value' => 'App\\GraphQL\\Mutations\\DeleteProductMutation',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'resolver',
                  ),
                ),
              ),
            ),
          ),
        ),
        3 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'syncProductToShopify',
          ),
          'arguments' => 
          array (
            0 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'id',
              ),
              'type' => 
              array (
                'kind' => 'NonNullType',
                'type' => 
                array (
                  'kind' => 'NamedType',
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'ID',
                  ),
                ),
              ),
              'directives' => 
              array (
              ),
            ),
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Product',
            ),
          ),
          'directives' => 
          array (
            0 => 
            array (
              'kind' => 'Directive',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'field',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'kind' => 'StringValue',
                    'value' => 'App\\GraphQL\\Mutations\\SyncProductToShopifyMutation',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'resolver',
                  ),
                ),
              ),
            ),
          ),
        ),
        4 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'toggleProductSyncAuto',
          ),
          'arguments' => 
          array (
            0 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'id',
              ),
              'type' => 
              array (
                'kind' => 'NonNullType',
                'type' => 
                array (
                  'kind' => 'NamedType',
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'ID',
                  ),
                ),
              ),
              'directives' => 
              array (
              ),
            ),
            1 => 
            array (
              'kind' => 'InputValueDefinition',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'sync_auto',
              ),
              'type' => 
              array (
                'kind' => 'NonNullType',
                'type' => 
                array (
                  'kind' => 'NamedType',
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'Boolean',
                  ),
                ),
              ),
              'directives' => 
              array (
              ),
            ),
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Product',
            ),
          ),
          'directives' => 
          array (
            0 => 
            array (
              'kind' => 'Directive',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'field',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'kind' => 'StringValue',
                    'value' => 'App\\GraphQL\\Mutations\\ToggleProductSyncAutoMutation',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'resolver',
                  ),
                ),
              ),
            ),
          ),
        ),
      ),
    ),
    'Product' => 
    array (
      'kind' => 'ObjectTypeDefinition',
      'name' => 
      array (
        'kind' => 'Name',
        'value' => 'Product',
      ),
      'interfaces' => 
      array (
      ),
      'directives' => 
      array (
      ),
      'fields' => 
      array (
        0 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'id',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'ID',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        1 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'shopify_id',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        2 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'handle',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        3 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'title',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'String',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        4 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'description',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        5 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'price',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'Float',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        6 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'compare_at_price',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Float',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        7 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'vendor',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        8 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'product_type',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        9 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'tags',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        10 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'status',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'String',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        11 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'sku',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        12 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'weight',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Float',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        13 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'weight_unit',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        14 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'requires_shipping',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Boolean',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        15 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'tracked',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Boolean',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        16 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'inventory_quantity',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Int',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        17 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'meta_title',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        18 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'meta_description',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        19 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'featured_image',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        20 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'template_suffix',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        21 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'published',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Boolean',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        22 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'published_at',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        23 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'sync_auto',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'Boolean',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        24 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'synced_at',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'String',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        25 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'created_at',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'String',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        26 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'updated_at',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'String',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
      ),
    ),
    'ProductPaginator' => 
    array (
      'kind' => 'ObjectTypeDefinition',
      'name' => 
      array (
        'kind' => 'Name',
        'value' => 'ProductPaginator',
      ),
      'interfaces' => 
      array (
      ),
      'directives' => 
      array (
      ),
      'fields' => 
      array (
        0 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'data',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'ListType',
              'type' => 
              array (
                'kind' => 'NonNullType',
                'type' => 
                array (
                  'kind' => 'NamedType',
                  'name' => 
                  array (
                    'kind' => 'Name',
                    'value' => 'Product',
                  ),
                ),
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        1 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'paginatorInfo',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'PaginatorInfo',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
      ),
    ),
    'PaginatorInfo' => 
    array (
      'kind' => 'ObjectTypeDefinition',
      'name' => 
      array (
        'kind' => 'Name',
        'value' => 'PaginatorInfo',
      ),
      'interfaces' => 
      array (
      ),
      'directives' => 
      array (
      ),
      'fields' => 
      array (
        0 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'count',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'Int',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        1 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'currentPage',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'Int',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        2 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'firstItem',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Int',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        3 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'hasMorePages',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'Boolean',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        4 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'lastItem',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NamedType',
            'name' => 
            array (
              'kind' => 'Name',
              'value' => 'Int',
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        5 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'lastPage',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'Int',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        6 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'perPage',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'Int',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
        7 => 
        array (
          'kind' => 'FieldDefinition',
          'name' => 
          array (
            'kind' => 'Name',
            'value' => 'total',
          ),
          'arguments' => 
          array (
          ),
          'type' => 
          array (
            'kind' => 'NonNullType',
            'type' => 
            array (
              'kind' => 'NamedType',
              'name' => 
              array (
                'kind' => 'Name',
                'value' => 'Int',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
        ),
      ),
    ),
    'SortOrder' => 
    array (
      'loc' => 
      array (
        'start' => 21,
        'end' => 301,
      ),
      'kind' => 'EnumTypeDefinition',
      'name' => 
      array (
        'loc' => 
        array (
          'start' => 91,
          'end' => 100,
        ),
        'kind' => 'Name',
        'value' => 'SortOrder',
      ),
      'directives' => 
      array (
      ),
      'values' => 
      array (
        0 => 
        array (
          'loc' => 
          array (
            'start' => 127,
            'end' => 189,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 186,
              'end' => 189,
            ),
            'kind' => 'Name',
            'value' => 'ASC',
          ),
          'directives' => 
          array (
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 127,
              'end' => 161,
            ),
            'kind' => 'StringValue',
            'value' => 'Sort records in ascending order.',
            'block' => false,
          ),
        ),
        1 => 
        array (
          'loc' => 
          array (
            'start' => 215,
            'end' => 279,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 275,
              'end' => 279,
            ),
            'kind' => 'Name',
            'value' => 'DESC',
          ),
          'directives' => 
          array (
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 215,
              'end' => 250,
            ),
            'kind' => 'StringValue',
            'value' => 'Sort records in descending order.',
            'block' => false,
          ),
        ),
      ),
      'description' => 
      array (
        'loc' => 
        array (
          'start' => 21,
          'end' => 65,
        ),
        'kind' => 'StringValue',
        'value' => 'Directions for ordering a list of records.',
        'block' => false,
      ),
    ),
    'OrderByRelationAggregateFunction' => 
    array (
      'loc' => 
      array (
        'start' => 21,
        'end' => 276,
      ),
      'kind' => 'EnumTypeDefinition',
      'name' => 
      array (
        'loc' => 
        array (
          'start' => 125,
          'end' => 157,
        ),
        'kind' => 'Name',
        'value' => 'OrderByRelationAggregateFunction',
      ),
      'directives' => 
      array (
      ),
      'values' => 
      array (
        0 => 
        array (
          'loc' => 
          array (
            'start' => 184,
            'end' => 254,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 227,
              'end' => 232,
            ),
            'kind' => 'Name',
            'value' => 'COUNT',
          ),
          'directives' => 
          array (
            0 => 
            array (
              'loc' => 
              array (
                'start' => 233,
                'end' => 254,
              ),
              'kind' => 'Directive',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 234,
                  'end' => 238,
                ),
                'kind' => 'Name',
                'value' => 'enum',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'loc' => 
                  array (
                    'start' => 239,
                    'end' => 253,
                  ),
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 246,
                      'end' => 253,
                    ),
                    'kind' => 'StringValue',
                    'value' => 'count',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 239,
                      'end' => 244,
                    ),
                    'kind' => 'Name',
                    'value' => 'value',
                  ),
                ),
              ),
            ),
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 184,
              'end' => 202,
            ),
            'kind' => 'StringValue',
            'value' => 'Amount of items.',
            'block' => false,
          ),
        ),
      ),
      'description' => 
      array (
        'loc' => 
        array (
          'start' => 21,
          'end' => 99,
        ),
        'kind' => 'StringValue',
        'value' => 'Aggregate functions when ordering by a relation without specifying a column.',
        'block' => false,
      ),
    ),
    'OrderByRelationWithColumnAggregateFunction' => 
    array (
      'loc' => 
      array (
        'start' => 21,
        'end' => 616,
      ),
      'kind' => 'EnumTypeDefinition',
      'name' => 
      array (
        'loc' => 
        array (
          'start' => 123,
          'end' => 165,
        ),
        'kind' => 'Name',
        'value' => 'OrderByRelationWithColumnAggregateFunction',
      ),
      'directives' => 
      array (
      ),
      'values' => 
      array (
        0 => 
        array (
          'loc' => 
          array (
            'start' => 192,
            'end' => 250,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 227,
              'end' => 230,
            ),
            'kind' => 'Name',
            'value' => 'AVG',
          ),
          'directives' => 
          array (
            0 => 
            array (
              'loc' => 
              array (
                'start' => 231,
                'end' => 250,
              ),
              'kind' => 'Directive',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 232,
                  'end' => 236,
                ),
                'kind' => 'Name',
                'value' => 'enum',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'loc' => 
                  array (
                    'start' => 237,
                    'end' => 249,
                  ),
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 244,
                      'end' => 249,
                    ),
                    'kind' => 'StringValue',
                    'value' => 'avg',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 237,
                      'end' => 242,
                    ),
                    'kind' => 'Name',
                    'value' => 'value',
                  ),
                ),
              ),
            ),
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 192,
              'end' => 202,
            ),
            'kind' => 'StringValue',
            'value' => 'Average.',
            'block' => false,
          ),
        ),
        1 => 
        array (
          'loc' => 
          array (
            'start' => 276,
            'end' => 334,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 311,
              'end' => 314,
            ),
            'kind' => 'Name',
            'value' => 'MIN',
          ),
          'directives' => 
          array (
            0 => 
            array (
              'loc' => 
              array (
                'start' => 315,
                'end' => 334,
              ),
              'kind' => 'Directive',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 316,
                  'end' => 320,
                ),
                'kind' => 'Name',
                'value' => 'enum',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'loc' => 
                  array (
                    'start' => 321,
                    'end' => 333,
                  ),
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 328,
                      'end' => 333,
                    ),
                    'kind' => 'StringValue',
                    'value' => 'min',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 321,
                      'end' => 326,
                    ),
                    'kind' => 'Name',
                    'value' => 'value',
                  ),
                ),
              ),
            ),
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 276,
              'end' => 286,
            ),
            'kind' => 'StringValue',
            'value' => 'Minimum.',
            'block' => false,
          ),
        ),
        2 => 
        array (
          'loc' => 
          array (
            'start' => 360,
            'end' => 418,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 395,
              'end' => 398,
            ),
            'kind' => 'Name',
            'value' => 'MAX',
          ),
          'directives' => 
          array (
            0 => 
            array (
              'loc' => 
              array (
                'start' => 399,
                'end' => 418,
              ),
              'kind' => 'Directive',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 400,
                  'end' => 404,
                ),
                'kind' => 'Name',
                'value' => 'enum',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'loc' => 
                  array (
                    'start' => 405,
                    'end' => 417,
                  ),
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 412,
                      'end' => 417,
                    ),
                    'kind' => 'StringValue',
                    'value' => 'max',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 405,
                      'end' => 410,
                    ),
                    'kind' => 'Name',
                    'value' => 'value',
                  ),
                ),
              ),
            ),
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 360,
              'end' => 370,
            ),
            'kind' => 'StringValue',
            'value' => 'Maximum.',
            'block' => false,
          ),
        ),
        3 => 
        array (
          'loc' => 
          array (
            'start' => 444,
            'end' => 498,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 475,
              'end' => 478,
            ),
            'kind' => 'Name',
            'value' => 'SUM',
          ),
          'directives' => 
          array (
            0 => 
            array (
              'loc' => 
              array (
                'start' => 479,
                'end' => 498,
              ),
              'kind' => 'Directive',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 480,
                  'end' => 484,
                ),
                'kind' => 'Name',
                'value' => 'enum',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'loc' => 
                  array (
                    'start' => 485,
                    'end' => 497,
                  ),
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 492,
                      'end' => 497,
                    ),
                    'kind' => 'StringValue',
                    'value' => 'sum',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 485,
                      'end' => 490,
                    ),
                    'kind' => 'Name',
                    'value' => 'value',
                  ),
                ),
              ),
            ),
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 444,
              'end' => 450,
            ),
            'kind' => 'StringValue',
            'value' => 'Sum.',
            'block' => false,
          ),
        ),
        4 => 
        array (
          'loc' => 
          array (
            'start' => 524,
            'end' => 594,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 567,
              'end' => 572,
            ),
            'kind' => 'Name',
            'value' => 'COUNT',
          ),
          'directives' => 
          array (
            0 => 
            array (
              'loc' => 
              array (
                'start' => 573,
                'end' => 594,
              ),
              'kind' => 'Directive',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 574,
                  'end' => 578,
                ),
                'kind' => 'Name',
                'value' => 'enum',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'loc' => 
                  array (
                    'start' => 579,
                    'end' => 593,
                  ),
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 586,
                      'end' => 593,
                    ),
                    'kind' => 'StringValue',
                    'value' => 'count',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 579,
                      'end' => 584,
                    ),
                    'kind' => 'Name',
                    'value' => 'value',
                  ),
                ),
              ),
            ),
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 524,
              'end' => 542,
            ),
            'kind' => 'StringValue',
            'value' => 'Amount of items.',
            'block' => false,
          ),
        ),
      ),
      'description' => 
      array (
        'loc' => 
        array (
          'start' => 21,
          'end' => 97,
        ),
        'kind' => 'StringValue',
        'value' => 'Aggregate functions when ordering by a relation that may specify a column.',
        'block' => false,
      ),
    ),
    'OrderByClause' => 
    array (
      'loc' => 
      array (
        'start' => 12,
        'end' => 278,
      ),
      'kind' => 'InputObjectTypeDefinition',
      'name' => 
      array (
        'loc' => 
        array (
          'start' => 67,
          'end' => 80,
        ),
        'kind' => 'Name',
        'value' => 'OrderByClause',
      ),
      'directives' => 
      array (
      ),
      'fields' => 
      array (
        0 => 
        array (
          'loc' => 
          array (
            'start' => 99,
            'end' => 170,
          ),
          'kind' => 'InputValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 155,
              'end' => 161,
            ),
            'kind' => 'Name',
            'value' => 'column',
          ),
          'type' => 
          array (
            'loc' => 
            array (
              'start' => 163,
              'end' => 170,
            ),
            'kind' => 'NonNullType',
            'type' => 
            array (
              'loc' => 
              array (
                'start' => 163,
                'end' => 169,
              ),
              'kind' => 'NamedType',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 163,
                  'end' => 169,
                ),
                'kind' => 'Name',
                'value' => 'String',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 99,
              'end' => 138,
            ),
            'kind' => 'StringValue',
            'value' => 'The column that is used for ordering.',
            'block' => false,
          ),
        ),
        1 => 
        array (
          'loc' => 
          array (
            'start' => 188,
            'end' => 264,
          ),
          'kind' => 'InputValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 247,
              'end' => 252,
            ),
            'kind' => 'Name',
            'value' => 'order',
          ),
          'type' => 
          array (
            'loc' => 
            array (
              'start' => 254,
              'end' => 264,
            ),
            'kind' => 'NonNullType',
            'type' => 
            array (
              'loc' => 
              array (
                'start' => 254,
                'end' => 263,
              ),
              'kind' => 'NamedType',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 254,
                  'end' => 263,
                ),
                'kind' => 'Name',
                'value' => 'SortOrder',
              ),
            ),
          ),
          'directives' => 
          array (
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 188,
              'end' => 230,
            ),
            'kind' => 'StringValue',
            'value' => 'The direction that is used for ordering.',
            'block' => false,
          ),
        ),
      ),
      'description' => 
      array (
        'loc' => 
        array (
          'start' => 12,
          'end' => 48,
        ),
        'kind' => 'StringValue',
        'value' => 'Allows ordering a list of records.',
        'block' => false,
      ),
    ),
    'Trashed' => 
    array (
      'loc' => 
      array (
        'start' => 25,
        'end' => 530,
      ),
      'kind' => 'EnumTypeDefinition',
      'name' => 
      array (
        'loc' => 
        array (
          'start' => 128,
          'end' => 135,
        ),
        'kind' => 'Name',
        'value' => 'Trashed',
      ),
      'directives' => 
      array (
      ),
      'values' => 
      array (
        0 => 
        array (
          'loc' => 
          array (
            'start' => 166,
            'end' => 250,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 225,
              'end' => 229,
            ),
            'kind' => 'Name',
            'value' => 'ONLY',
          ),
          'directives' => 
          array (
            0 => 
            array (
              'loc' => 
              array (
                'start' => 230,
                'end' => 250,
              ),
              'kind' => 'Directive',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 231,
                  'end' => 235,
                ),
                'kind' => 'Name',
                'value' => 'enum',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'loc' => 
                  array (
                    'start' => 236,
                    'end' => 249,
                  ),
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 243,
                      'end' => 249,
                    ),
                    'kind' => 'StringValue',
                    'value' => 'only',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 236,
                      'end' => 241,
                    ),
                    'kind' => 'Name',
                    'value' => 'value',
                  ),
                ),
              ),
            ),
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 166,
              'end' => 196,
            ),
            'kind' => 'StringValue',
            'value' => 'Only return trashed results.',
            'block' => false,
          ),
        ),
        1 => 
        array (
          'loc' => 
          array (
            'start' => 280,
            'end' => 380,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 355,
              'end' => 359,
            ),
            'kind' => 'Name',
            'value' => 'WITH',
          ),
          'directives' => 
          array (
            0 => 
            array (
              'loc' => 
              array (
                'start' => 360,
                'end' => 380,
              ),
              'kind' => 'Directive',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 361,
                  'end' => 365,
                ),
                'kind' => 'Name',
                'value' => 'enum',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'loc' => 
                  array (
                    'start' => 366,
                    'end' => 379,
                  ),
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 373,
                      'end' => 379,
                    ),
                    'kind' => 'StringValue',
                    'value' => 'with',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 366,
                      'end' => 371,
                    ),
                    'kind' => 'Name',
                    'value' => 'value',
                  ),
                ),
              ),
            ),
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 280,
              'end' => 326,
            ),
            'kind' => 'StringValue',
            'value' => 'Return both trashed and non-trashed results.',
            'block' => false,
          ),
        ),
        2 => 
        array (
          'loc' => 
          array (
            'start' => 410,
            'end' => 504,
          ),
          'kind' => 'EnumValueDefinition',
          'name' => 
          array (
            'loc' => 
            array (
              'start' => 473,
              'end' => 480,
            ),
            'kind' => 'Name',
            'value' => 'WITHOUT',
          ),
          'directives' => 
          array (
            0 => 
            array (
              'loc' => 
              array (
                'start' => 481,
                'end' => 504,
              ),
              'kind' => 'Directive',
              'name' => 
              array (
                'loc' => 
                array (
                  'start' => 482,
                  'end' => 486,
                ),
                'kind' => 'Name',
                'value' => 'enum',
              ),
              'arguments' => 
              array (
                0 => 
                array (
                  'loc' => 
                  array (
                    'start' => 487,
                    'end' => 503,
                  ),
                  'kind' => 'Argument',
                  'value' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 494,
                      'end' => 503,
                    ),
                    'kind' => 'StringValue',
                    'value' => 'without',
                    'block' => false,
                  ),
                  'name' => 
                  array (
                    'loc' => 
                    array (
                      'start' => 487,
                      'end' => 492,
                    ),
                    'kind' => 'Name',
                    'value' => 'value',
                  ),
                ),
              ),
            ),
          ),
          'description' => 
          array (
            'loc' => 
            array (
              'start' => 410,
              'end' => 444,
            ),
            'kind' => 'StringValue',
            'value' => 'Only return non-trashed results.',
            'block' => false,
          ),
        ),
      ),
      'description' => 
      array (
        'loc' => 
        array (
          'start' => 25,
          'end' => 98,
        ),
        'kind' => 'StringValue',
        'value' => 'Specify if you want to include or exclude trashed results from a query.',
        'block' => false,
      ),
    ),
  ),
  'directives' => 
  array (
  ),
  'classNameToObjectTypeName' => 
  array (
  ),
  'schemaExtensions' => 
  array (
  ),
  'hash' => 'f24fec60c7745bd292868e5f8be17eca1315679395575912e73387d91788e7fc',
);