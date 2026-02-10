<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Batch;
use App\Models\Issue;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Stwórz Stanowiska
        $positions = Position::factory(5)->create();

        // 2. Stwórz Pracowników (50 sztuk)
        $employees = Employee::factory(50)
            ->recycle($positions) // Przypisz ich losowo do powyższych stanowisk
            ->create();

        // 3. Stwórz Produkty i Partie
        $products = Product::factory(20)->create();
        
        $batches = collect();
        foreach($products as $product) {
            // Do każdego produktu dorób 3 partie (różne rozmiary/daty)
            $batches = $batches->merge(
                Batch::factory(3)->create(['product_id' => $product->id])
            );
        }

        // 4. GENEROWANIE WYDAŃ (To nas interesuje najbardziej)
        
        foreach($employees as $employee) {
            // A. Każdy pracownik ma 3-5 starych, zwróconych rzeczy (Archiwum)
            Issue::factory(rand(3, 5))
                ->state(['returned_at' => now()->subDays(rand(1, 300))])
                ->for($employee)
                ->for($batches->random())
                ->create();

            // B. Co 5-ty pracownik ma coś PRZETERMINOWANEGO (Czerwone)
            if($employee->id % 5 === 0) {
                Issue::factory()->overdue()
                    ->for($employee)
                    ->for($batches->random())
                    ->create();
            }

            // C. Co 3-ci pracownik ma coś WYGASAJĄCEGO (Żółte)
            if($employee->id % 3 === 0) {
                Issue::factory()->dueSoon()
                    ->for($employee)
                    ->for($batches->random())
                    ->create();
            }

            // D. Każdy ma 1-2 aktywne, zielone rzeczy
            Issue::factory(rand(1, 2))->active()
                ->for($employee)
                ->for($batches->random())
                ->create();
        }
    }
}