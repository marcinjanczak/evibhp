<?php

namespace App\Livewire\Issues;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Services\IssueService;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Batch;

class IssueForm extends Component
{
    #[Validate('required|exists:employees,id')]
    public $employee_id = '';

    #[Validate('required|exists:products,id')]
    public $product_id = '';

    #[Validate('required|exists:batches,id')]
    public $batch_id = '';

    #[Validate('required|integer|min:1')]
    public $quantity = 1;

    // Przechowujemy ID produktów sugerowanych dla wybranego pracownika
    public $suggestedProductIds = [];

    // Gdy zmienia się pracownik -> sprawdź jego stanowisko i pobierz sugerowane ID
    public function updatedEmployeeId($value)
    {
        $this->reset(['product_id', 'batch_id', 'suggestedProductIds']);
        
        if ($value) {
            $employee = Employee::with('position.products')->find($value);
            if ($employee && $employee->position) {
                // Pobieramy ID produktów przypisanych do stanowiska tego pracownika
                $this->suggestedProductIds = $employee->position->products->pluck('id')->toArray();
            }
        }
    }

    // Gdy zmienia się produkt -> resetujemy partię (żeby nie został stary rozmiar)
    public function updatedProductId()
    {
        $this->reset('batch_id');
    }

    public function save(IssueService $service)
    {
        $this->validate();

        try {
            $service->createIssue([
                'employee_id' => $this->employee_id,
                'batch_id'    => $this->batch_id,
                'quantity'    => $this->quantity,
            ]);

            session()->flash('success', 'Towar wydany pomyślnie!');
            $this->redirectRoute('issues.index'); // Przekierowanie do historii wydań

        } catch (\Exception $e) {
            $this->addError('quantity', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.issues.issue-form', [
            'employees' => Employee::with('position')->orderBy('last_name')->get(),
            
            // Pobieramy produkty
            'products' => Product::orderBy('name')->get(),
            
            // Pobieramy partie TYLKO dla wybranego produktu, które mają stan > 0
            'batches' => $this->product_id 
                ? Batch::where('product_id', $this->product_id)
                       ->where('current_quantity', '>', 0)
                       ->orderBy('expiration_date') // Najpierw najstarsze (FIFO - First In First Out)
                       ->get() 
                : collect(),
        ]);
    }
}