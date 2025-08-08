<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wypozyczone', function (Blueprint $table) {
            $table->id('IdWypozyczenia');
            $table->unsignedBigInteger('IdPracownika')->nullable();
            $table->unsignedInteger('IdPrzedmiot')->nullable();
            $table->integer('Ilosc')->nullable();
            $table->DATETIME('Data')->nullable();
            $table->DATETIME('DataDoZwrotu')->nullable();
            $table->DATETIME('DataZwrotu')->nullable();

            $table->foreign('IdPracownika')->references('id')->on('pracownicy')->onDelete('cascade');
            $table->foreign('IdPrzedmiot')->references('IdPrzedmiot')->on('przedmiot')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wypozyczone');
    }
};
