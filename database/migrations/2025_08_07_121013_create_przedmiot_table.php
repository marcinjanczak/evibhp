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
        Schema::create('Przedmiot', function (Blueprint $table) {
            $table->id();
            $table->string('nazwa', 50);
            $table->string('typ', 50);
            $table->string('rozmiar', 50);
            $table->integer('ilosc_dodanych')->default(0);
            $table->string('faktura_pdf_path')->nullable();
            $table->string('zdjecie_pogladowe_path')->nullable();
            $table->date('data_waznosci')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Przedmiot');
    }
};
