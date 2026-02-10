<?php

namespace App\Services;

use App\Models\Issue;
use App\Models\Batch;
use Illuminate\Support\Facades\DB;
use Exception;

class IssueService
{
   public function createIssue(array $data): Issue
    {
        return DB::transaction(function () use ($data) {
            $batch = Batch::where('id', $data['batch_id'])->lockForUpdate()->firstOrFail();

            if ($batch->current_quantity < $data['quantity']) {
                throw new Exception("Brak wystarczającej ilości towaru. Dostępne: {$batch->current_quantity} szt.");
            }

            $batch->decrement('current_quantity', $data['quantity']);

            return Issue::create([
                'employee_id' => $data['employee_id'],
                'batch_id'    => $data['batch_id'],
                'quantity'    => $data['quantity'],
                'issued_at'   => now(),
                'due_date'    => $data['due_date'] ?? null,
                'returned_at' => null,
            ]);
        });
    }

    public function archiveIssue(Issue $issue, ?string $note = null): bool
    {
        if ($issue->returned_at !== null) {
            throw new Exception("To wydanie zostało już zarchiwizowane.");
        }

        return DB::transaction(function () use ($issue) {
            $issue->batch->increment('current_quantity', $issue->quantity);

            $issue->update([
                'returned_at' => now(),
            ]);

            return true;
        });
    }
}