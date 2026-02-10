<?php

namespace App\Livewire\Issues;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Services\IssueService;
use App\Models\Employee;
use App\Models\Batch;
use App\Models\Product;
use Carbon\Carbon;

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

    #[Validate('nullable|date|after:today')]

    public $due_date = '';

    public $selectedMonths = null;

    public $suggestedProductIds = [];

    public $searchEmployee = '';

    public $selectedEmployeeId = null;

    public function updatedEmployeeId($value)
    {
        $this->reset(['product_id', 'batch_id', 'suggestedProductIds']);
        
        if ($value) {
            $employee = Employee::with('position.products')->find($value);
            if ($employee && $employee->position) {
                $this->suggestedProductIds = $employee->position->products->pluck('id')->toArray();
            }
        }
    }

    public function updatedProductId()
    {
        $this->reset('batch_id');
    }

    public function setPeriod($months)
    {
        $this->selectedMonths = $months;
        $this->due_date = Carbon::now()->addMonths($months)->format('Y-m-d');
    }

   public function selectEmployee($id)
    {
        if ($this->selectedEmployeeId == $id) {
            $this->selectedEmployeeId = null;
            $this->employee_id = null; 
            $this->suggestedProductIds = [];
            return;
        }

        $this->selectedEmployeeId = $id;
        $this->employee_id = $id; 

        $employee = Employee::with('position')->find($id); 

        if ($employee && $employee->position) {
            $this->suggestedProductIds = $employee->position->products()
                ->pluck('products.id') 
                ->toArray();
        } else {
            $this->suggestedProductIds = [];
        }
    }

    public function selectProduct($id)
    {
        if ($this->product_id == $id) {
            $this->product_id = null;
            $this->batch_id = null; 
            return;
        }

        $this->product_id = $id;
        
        $this->batch_id = null; 
    }

    public function save(IssueService $service)
    {
        $this->validate();

        try {
            $service->createIssue([
                'employee_id' => $this->employee_id,
                'batch_id'    => $this->batch_id,
                'quantity'    => $this->quantity,
                'due_date'    => $this->due_date,
            ]);

            session()->flash('success', 'Towar wydany pomyślnie!');
            $this->redirectRoute('issues.index'); 

        } catch (\Exception $e) {
            $this->addError('quantity', $e->getMessage());
        }
    }

    public function render()
    {
        $productsQuery = Product::with('batches');

        if (!empty($this->searchProduct)) {
            $productsQuery->where('name', 'like', '%'.$this->searchProduct.'%');
        }

        $products = $productsQuery->orderBy('name')->get();


        return view('livewire.issues.issue-form', [
            'employees' => Employee::with('position')
                        ->when($this->searchEmployee, function($q) {
                            $q->where('last_name', 'like', '%'.$this->searchEmployee.'%');
                        })
                        ->limit(10)
                        ->get(),
            
            'products' => $products,
            
            'batches' => $this->product_id 
                ? Batch::where('product_id', $this->product_id)
                       ->where('current_quantity', '>', 0)
                       ->orderBy('expiration_date') 
                       ->get() 
                : collect(),
        ]);
    }
}