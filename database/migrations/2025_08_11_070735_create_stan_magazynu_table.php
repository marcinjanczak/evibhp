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
        Schema::create('stan_magazynu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdPrzedmiot')
            ->constant('przedmiot')
            ->onDelete('cascade');
            $table->integer('Ilosc')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stan_magazynu');
    }
};
