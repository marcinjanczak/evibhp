<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Batch;
use App\Models\Employee;
use App\Services\IssueService;
use Livewire\Attributes\Validate;

class IssueForm extends Component
{
    #[Validate('required|exists:employees,id')]
    public $employee_id;

    #[Validate('required|exists:batches,id')]
    public $batch_id;

    #[Validate('required|integer|min:1')]
    public $quantity = 1;

    public $product_id;
    public $search = '';

    // Livewire 4 automatycznie obsługuje wstrzykiwanie zależności w metodzie save
    public function save(IssueService $service)
    {
        $this->validate();

        try {
            $service->createIssue([
                'employee_id' => $this->employee_id,
                'batch_id'    => $this->batch_id,
                'quantity'    => $this->quantity,
                'issued_at'   => now(),
            ]);

            // Flash message i przekierowanie działają tak samo
            session()->flash('success', 'Wydano towar z magazynu!');
            return $this->redirectRoute('issues.index', navigate: true); 
            // navigate: true to nowość w v4 (SPA mode) - strona się nie przeładowuje!

        } catch (\Exception $e) {
            $this->addError('quantity', $e->getMessage());
        }
    }

    // Reset partii przy zmianie produktu
    public function updatedProductId()
    {
        $this->reset('batch_id');
    }

    public function render()
    {
        return view('livewire.issue-form', [
            'employees' => Employee::orderBy('last_name')->get(),
            'products'  => Product::where('name', 'like', "%{$this->search}%")->get(),
            'batches'   => Batch::where('product_id', $this->product_id)
                ->where('current_quantity', '>', 0)
                ->get(),
        ]);
    }
}