<?php

namespace App\Livewire\ProductsComponents;

use App\Models\Batch;
use App\Models\Employee;
use App\Models\Issue;
use Livewire\Component;
use Livewire\Attributes\On;

class IssueBatchModal extends Component
{
    public $batch_id;
    public $employee_id;
    public $quantity = 1;
    public $searchEmployee = '';

    #[On('open-issue-batch-modal')]
    public function openModal($batchId)
    {
        $this->batch_id = $batchId;
        $this->reset(['employee_id', 'searchEmployee', 'quantity']);
        $this->quantity = 1;
    }

    public function issueItem()
    {
        $this->validate([
            'batch_id' => 'required|exists:batches,id',
            'employee_id' => 'required|exists:employees,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $batch = Batch::findOrFail($this->batch_id);

        if ($this->quantity > $batch->current_quantity) {
            $this->addError('quantity', 'Brak wystarczającej ilości w tej partii.');
            return;
        }

        Issue::create([
            'employee_id' => $this->employee_id,
            'batch_id' => $this->batch_id,
            'quantity' => $this->quantity,
            'issued_at' => now(),
        ]);

        $batch->decrement('current_quantity', $this->quantity);

        session()->flash('success', 'Wydano przedmiot pracownikowi.');
        $this->dispatch('close-modal-issue');
        
        // Refresh page to show updated stock
        return redirect()->route('items.show', $batch->product_id);
    }

    public function selectEmployee($id, $name)
    {
        $this->employee_id = $id;
        $this->searchEmployee = $name;
    }

    public function render()
    {
        $employees = Employee::query()
            ->when($this->searchEmployee, function ($query) {
                $query->where('first_name', 'like', '%' . $this->searchEmployee . '%')
                      ->orWhere('last_name', 'like', '%' . $this->searchEmployee . '%');
            })
            ->take(5)
            ->get();

        $batch = $this->batch_id ? Batch::find($this->batch_id) : null;

        return view('livewire.products-components.issue-batch-modal', [
            'employees' => $employees,
            'batch' => $batch,
        ]);
    }
}
