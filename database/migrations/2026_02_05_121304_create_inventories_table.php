<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('inventories', function (Blueprint $table) {
        $table->id();
        // Laravel sam zgadnie, że chodzi o tabelę 'products' i kolumnę 'id'
        $table->foreignId('product_id')->constrained()->onDelete('cascade');
        $table->integer('quantity')->default(0);
        $table->timestamps(); // Warto mieć timestampy, żeby wiedzieć kiedy był ostatni update stanu
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};
