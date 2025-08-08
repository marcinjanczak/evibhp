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
            $table->unsignedInteger('IdPrzedmiot');
            $table->foreign('IdPrzedmiot')->references('IdPrzedmiot')->on('przedmiot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stanprzedmiotow');
    }
};