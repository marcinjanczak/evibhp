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
        Schema::create('pracownicy', function (Blueprint $table) {
        $table->id();
        $table->string("imie");
        $table->string("nazwisko");
        $table->timestamps();
        });
    }

public function getImieAttribute($value)
{
    return $this->attributes['Imie'] ?? null;
}

public function getNazwiskoAttribute($value)
{
    return $this->attributes['Nazwisko'] ?? null;
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pracownicy');
    }

};
