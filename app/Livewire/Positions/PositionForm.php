<?php

namespace App\Livewire\Positions;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Position;
use App\Models\Product;

class PositionForm extends Component
{
    public ?Position $position = null;
    public $name = '';
    public $selectedProducts = []; 

    public function save()
    {
        $this->validate(['name' => 'required|string|max:100|unique:positions,name,' . ($this->position->id ?? 'NULL')]);

        if ($this->position) {
            $this->position->update(['name' => $this->name]);
            $this->position->products()->sync($this->selectedProducts);
            session()->flash('success', 'Stanowisko zaktualizowane.');
        } else {
            $pos = Position::create(['name' => $this->name]);
            $pos->products()->attach($this->selectedProducts); 
            session()->flash('success', 'Stanowisko dodane.');
        }

        return $this->redirectRoute('positions.index', navigate: true);
    }

    #[On('edit-position')] 
    public function loadPosition(int $id)
    {
        $this->position = Position::find($id);
        $this->name = $this->position->name;
        $this->selectedProducts = $this->position->products()->pluck('products.id')->toArray();
    }

    #[On('reset-position-form')]
    public function resetForm()
    {
        $this->reset(['position', 'name', 'selectedProducts']);
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.positions.position-form', [
            'allProducts' => Product::orderBy('name')->get()
        ]);
    }
}