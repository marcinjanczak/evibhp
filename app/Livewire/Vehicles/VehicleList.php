<?php

namespace App\Livewire\Vehicles;

use Livewire\Component;
use App\Models\Vehicle;
use Livewire\Attributes\On;

class VehicleList extends Component
{
    public $make;
    public $license_plate;
    
    public $search = '';
    
    // Zmienne do edycji
    public $edit_id;
    public $edit_make;
    public $edit_license_plate;

    protected function rules()
    {
        return [
            'make' => 'required|string|max:255',
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate',
            'edit_make' => 'required|string|max:255',
            'edit_license_plate' => 'required|string|max:50|unique:vehicles,license_plate,' . $this->edit_id,
        ];
    }

    protected $messages = [
        'make.required' => 'Marka i model są wymagane.',
        'license_plate.required' => 'Numer rejestracyjny jest wymagany.',
        'license_plate.unique' => 'Pojazd o takiej rejestracji już istnieje.',
        'edit_make.required' => 'Marka i model są wymagane.',
        'edit_license_plate.required' => 'Numer rejestracyjny jest wymagany.',
        'edit_license_plate.unique' => 'Pojazd o takiej rejestracji już istnieje.',
    ];

    public function addVehicle()
    {
        $this->validate([
            'make' => 'required|string|max:255',
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate',
        ]);

        Vehicle::create([
            'make' => $this->make,
            'license_plate' => $this->license_plate,
        ]);

        $this->reset(['make', 'license_plate']);
        session()->flash('success', 'Pojazd dodany pomyślnie!');
        $this->dispatch('close-modal');
        $this->dispatch('vehicle-updated');
    }

    public function editVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $this->edit_id = $vehicle->id;
        $this->edit_make = $vehicle->make;
        $this->edit_license_plate = $vehicle->license_plate;
    }

    public function updateVehicle()
    {
        $this->validate([
            'edit_make' => 'required|string|max:255',
            'edit_license_plate' => 'required|string|max:50|unique:vehicles,license_plate,' . $this->edit_id,
        ]);

        $vehicle = Vehicle::findOrFail($this->edit_id);
        $vehicle->update([
            'make' => $this->edit_make,
            'license_plate' => $this->edit_license_plate,
        ]);

        $this->reset(['edit_id', 'edit_make', 'edit_license_plate']);
        session()->flash('success', 'Pojazd zaktualizowany!');
        $this->dispatch('close-modal-edit');
        $this->dispatch('vehicle-updated');
    }

    public function deleteVehicle($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        if ($vehicle->trips()->count() > 0) {
            session()->flash('error', 'Nie można usunąć pojazdu, ponieważ posiada historię wyjazdów!');
            return;
        }
        
        $vehicle->delete();
        session()->flash('success', 'Pojazd został usunięty.');
        $this->dispatch('vehicle-updated');
    }

    #[On('trip-updated')]
    public function refreshVehicles() {}

    public function render()
    {
        $query = Vehicle::withCount(['trips' => function($q) {
            $q->where('status', 'in_progress');
        }]);

        if (strlen($this->search) > 0) {
            $query->where('make', 'like', '%' . $this->search . '%')
                  ->orWhere('license_plate', 'like', '%' . $this->search . '%');
        }

        return view('livewire.vehicles.vehicle-list', [
            'vehicles' => $query->orderBy('make')->get()
        ]);
    }
}
