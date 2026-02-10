<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class BatchFactory extends Factory
{
    public function definition(): array
    {
        $qty = $this->faker->numberBetween(10, 50);

        return [
            // Jeśli produkt nie podany, stwórz nowy
            'product_id' => Product::factory(),
            
            'batch_number' => $this->faker->bothify('BATCH-####-??'),
            'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL', '42', '44', 'Universal']),
            
            // TU BYŁ BŁĄD: Zmieniamy 'quantity' na 'initial_quantity'
            'initial_quantity' => $qty, 
            'current_quantity' => $qty, // Na start tyle samo co initial
            
            'expiration_date' => $this->faker->dateTimeBetween('+1 year', '+5 years'),
            
            // Usuwam 'price' i 'purchase_date', bo nie masz ich w migracji którą pokazałeś
        ];
    }
}