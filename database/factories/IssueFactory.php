<?php

namespace Database\Factories;

use App\Models\Batch;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class IssueFactory extends Factory
{
    public function definition(): array
    {
        // Losujemy datę wydania (od 2 lat temu do wczoraj)
        $issuedAt = $this->faker->dateTimeBetween('-2 years', '-1 day');
        $issuedAtCarbon = Carbon::instance($issuedAt);

        // Termin ważności: Losowo od 6 miesięcy do 2 lat od wydania
        $dueDate = (clone $issuedAtCarbon)->addDays($this->faker->numberBetween(180, 730));

        // Czy zwrócony? (70% szans na zwrot - żeby zapełnić archiwum)
        $isReturned = $this->faker->boolean(70); 
        
        $returnedAt = null;
        if ($isReturned) {
            // Jeśli zwrócony, to data zwrotu musi być PO dacie wydania
            $returnedAt = $this->faker->dateTimeBetween($issuedAt, 'now');
        }

        return [
            'employee_id' => Employee::factory(), // Domyślnie utworzy nowego, jeśli nie podamy
            'batch_id'    => Batch::factory(),    // Domyślnie utworzy nową partię
            'quantity'    => $this->faker->numberBetween(1, 3),
            'issued_at'   => $issuedAt,
            'due_date'    => $dueDate,
            'returned_at' => $returnedAt,
            'created_at'  => $issuedAt, // Żeby sortowanie po created_at działało
            'updated_at'  => $returnedAt ?? $issuedAt,
        ];
    }

    // --- STANY (Scenariusze testowe) ---

    // Stan: Przeterminowane (Czerwone)
    public function overdue()
    {
        return $this->state(function (array $attributes) {
            return [
                'issued_at' => Carbon::now()->subDays(400),
                'due_date'  => Carbon::now()->subDays(10), // Termin minął 10 dni temu
                'returned_at' => null,
            ];
        });
    }

    public function dueSoon()
    {
        return $this->state(function (array $attributes) {
            return [
                'issued_at' => Carbon::now()->subDays(300),
                'due_date'  => Carbon::now()->addDays(15), // Termin za 15 dni
                'returned_at' => null,
            ];
        });
    }

    // Stan: Bezpieczne (Zielone)
    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'issued_at' => Carbon::now()->subDays(30),
                'due_date'  => Carbon::now()->addDays(200), // Termin za 200 dni
                'returned_at' => null,
            ];
        });
    }
}