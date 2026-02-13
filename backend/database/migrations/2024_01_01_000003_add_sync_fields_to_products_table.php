<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tornar shopify_id nullable (produtos criados localmente não têm shopify_id inicialmente)
            $table->string('shopify_id')->nullable()->change();
            
            // Adicionar campo para controlar sincronização automática
            $table->boolean('sync_auto')->default(false)->after('status');
        });

        // Garantir que produtos existentes tenham sync_auto = false
        \DB::table('products')->whereNull('sync_auto')->update(['sync_auto' => false]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('sync_auto');
            $table->string('shopify_id')->nullable(false)->change();
        });
    }
};
