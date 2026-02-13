<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('shopify_id')->nullable()->unique()->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('vendor')->nullable()->index();
            $table->string('product_type')->nullable()->index();
            $table->string('status')->default('active')->index();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            $table->index(['vendor', 'product_type']);
            $table->index(['status', 'synced_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
