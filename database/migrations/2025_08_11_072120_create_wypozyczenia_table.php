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
        Schema::create('wypozyczenia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('IdPracownika')
                ->constrained('pracownicy')
                ->onDelete('cascade');

            $table->foreignId('IdPrzedmiot')
                ->constrained('przedmiot')
                ->onDelete('cascade');

            $table->integer('Ilosc');
            $table->date('DataWypozyczenia');
            $table->date('DataPlanowanegoZwrotu')->nullable(); // Może być null
            $table->date('DataRzeczywistegoZwrotu')->nullable(); // Może być null
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wypozyczenia');
    }
};
