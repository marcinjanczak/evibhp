<?php

namespace App\Livewire\ProductsComponents;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use App\Models\Product;
use App\Services\BatchService;

class BatchForm extends Component
{
    use WithFileUploads;

    public Product $product; 

    #[Validate('required|string|max:50')]
    public $size = '';

    #[Validate('nullable|string|max:100')]
    public $batch_number = '';

    #[Validate('required|integer|min:1')]
    public $quantity = 1;

    #[Validate('required|date')]
    public $expiration_date;

    #[Validate('nullable|mimes:pdf|max:5120')]
    public $invoice_pdf;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->expiration_date = now()->addYears(2)->format('Y-m-d');
    }

    public function save(BatchService $batchService)
    {
        $this->validate();

        try {
            $batchService->addBatchToProduct($this->product, [
                'size'            => $this->size,
                'batch_number'    => $this->batch_number,
                'quantity'        => $this->quantity,
                'expiration_date' => $this->expiration_date,
                'invoice_pdf'     => $this->invoice_pdf,
            ]);

            $this->reset(['size', 'batch_number', 'quantity', 'invoice_pdf']);
            
            session()->flash('success', 'Nowa partia towaru została przyjęta na stan.');

            return $this->redirectRoute('items.show', $this->product->id, navigate: true);

        } catch (\Exception $e) {
            $this->addError('base', 'Błąd zapisu: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.products-components.batch-form');
    }
}