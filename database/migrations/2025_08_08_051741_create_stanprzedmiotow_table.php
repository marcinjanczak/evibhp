<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stanprzedmiotow', function (Blueprint $table) {
            $table->increments('Id');
            $table->integer('Ilosc');
            $table->unsignedBigInteger('IdPrzedmiot');
            $table->foreign('IdPrzedmiot')->references('id')->on('Przedmiot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stanprzedmiotow');
    }
};