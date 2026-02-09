<?php

namespace App\Livewire\Positions;

use Livewire\Component;
use Livewire\Attributes\On; 
use App\Models\Position;
use App\Models\Product;

class PositionForm extends Component
{
    public ?Position $position = null; // Jeśli null = tryb dodawania, jeśli obiekt = edycja
    public $name = '';
    public $selectedProducts = []; // Tablica ID wybranych produktów

    public function save()
    {
        $this->validate(['name' => 'required|string|max:100|unique:positions,name,' . ($this->position->id ?? 'NULL')]);

        if ($this->position) {
            // Aktualizacja
            $this->position->update(['name' => $this->name]);
            $this->position->products()->sync($this->selectedProducts); // Zapisujemy relacje
            session()->flash('success', 'Stanowisko zaktualizowane.');
        } else {
            // Tworzenie
            $pos = Position::create(['name' => $this->name]);
            $pos->products()->attach($this->selectedProducts); // Zapisujemy relacje
            session()->flash('success', 'Stanowisko dodane.');
        }

        return $this->redirectRoute('positions.index', navigate: true);
    }

    // Obsługa edycji (ładowanie danych do formularza)
    #[On('edit-position')] 
    public function loadPosition(int $id)
    {
        $this->position = Position::find($id);
        $this->name = $this->position->name;
        // Pobieramy ID produktów przypisanych do tego stanowiska
        $this->selectedProducts = $this->position->products()->pluck('id')->toArray();
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
            // Pobieramy listę wszystkich produktów do wyboru
            'allProducts' => Product::orderBy('name')->get()
        ]);
    }
}