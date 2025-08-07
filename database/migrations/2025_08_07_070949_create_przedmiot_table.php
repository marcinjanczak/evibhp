<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('przedmiot', function (Blueprint $table) {
            $table->increments('IdPrzedmiot');
            $table->char('Nazwa', 100);
            $table->char('Typ', 30);
            $table->char('Rozmiar', 1)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('przedmiot');
    }
};