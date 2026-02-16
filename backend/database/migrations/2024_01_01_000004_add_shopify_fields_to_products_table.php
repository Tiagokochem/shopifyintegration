<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Basic Shopify fields
            $table->string('handle')->nullable()->unique()->after('shopify_id');
            $table->string('tags')->nullable()->after('product_type');
            
            // Pricing fields
            $table->decimal('compare_at_price', 10, 2)->nullable()->after('price');
            
            // Inventory fields
            $table->string('sku')->nullable()->index()->after('compare_at_price');
            $table->decimal('weight', 10, 2)->nullable()->after('sku');
            $table->string('weight_unit')->default('kg')->after('weight');
            $table->boolean('requires_shipping')->default(true)->after('weight_unit');
            $table->boolean('tracked')->default(false)->after('requires_shipping');
            $table->integer('inventory_quantity')->nullable()->after('tracked');
            
            // SEO fields
            $table->string('meta_title')->nullable()->after('inventory_quantity');
            $table->text('meta_description')->nullable()->after('meta_title');
            
            // Media fields (JSON to store multiple images)
            $table->json('images')->nullable()->after('meta_description');
            $table->string('featured_image')->nullable()->after('images');
            
            // Additional Shopify fields
            $table->string('template_suffix')->nullable()->after('featured_image');
            $table->boolean('published')->default(true)->after('template_suffix');
            $table->timestamp('published_at')->nullable()->after('published');
            
            // Variants summary (JSON to store variant data)
            $table->json('variants_data')->nullable()->after('published_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'handle',
                'tags',
                'compare_at_price',
                'sku',
                'weight',
                'weight_unit',
                'requires_shipping',
                'tracked',
                'inventory_quantity',
                'meta_title',
                'meta_description',
                'images',
                'featured_image',
                'template_suffix',
                'published',
                'published_at',
                'variants_data',
            ]);
        });
    }
};
