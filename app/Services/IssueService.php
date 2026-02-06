<?php

namespace App\Services;

use App\Models\Issue;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;
use Exception;

class IssueService
{
    /**
     * Wydaje towar pracownikowi i aktualizuje stan magazynowy partii.
     */
    public function createIssue(array $data): Issue
    {
        return DB::transaction(function () use ($data) {
            // 1. Znajdź partię (dostawę)
            $batch = Batch::lockForUpdate()->findOrFail($data['batch_id']);

            // 2. Sprawdź czy jest wystarczająca ilość
            if ($batch->current_quantity < $data['quantity']) {
                throw new Exception("Brak wystarczającej ilości towaru w tej partii. Dostępne: {$batch->current_quantity} szt.");
            }

            // 3. Odejmij ze stanu
            $batch->decrement('current_quantity', $data['quantity']);

            // 4. Zapisz wydanie
            return Issue::create([
                'employee_id' => $data['employee_id'],
                'batch_id'    => $data['batch_id'],
                'quantity'    => $data['quantity'],
                'issued_at'   => $data['issued_at'] ?? now(),
                'due_date'    => $data['due_date'] ?? null,
            ]);
        });
    }

    /**
     * Przyjmuje zwrot towaru na magazyn.
     */
    public function returnIssue(Issue $issue, ?string $note = null): bool
    {
        if ($issue->returned_at) {
            throw new Exception("Ten przedmiot został już zwrócony.");
        }

        return DB::transaction(function () use ($issue) {
            // 1. Zwracamy towar na stan partii
            $issue->batch->increment('current_quantity', $issue->quantity);

            // 2. Oznaczamy jako zwrócone
            $issue->update([
                'returned_at' => now(),
            ]);

            return true;
        });
    }
}