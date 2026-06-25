<?php

namespace App\Livewire\Vehicles;

use Livewire\Component;
use App\Models\VehicleTrip;
use App\Models\Vehicle;
use App\Models\Employee;
use App\Models\Position;

class TripList extends Component
{
    // Start Trip
    public $vehicle_id;
    public $employee_id;
    public $selectedEmployeeName = '';
    public $searchEmployee = '';
    public $departure_date;
    public $departure_time_h;
    public $departure_time_m;
    public $estimated_return_time_h;
    public $estimated_return_time_m;
    public $departure_location;
    public $destination;

    public function mount()
    {
        $this->departure_date = date('Y-m-d');
    }

    // End Trip
    public $end_trip_id;
    public $return_date;
    public $return_time_h;
    public $return_time_m;

    protected $rules = [
        'vehicle_id' => 'required|exists:vehicles,id',
        'employee_id' => 'required|exists:employees,id',
        'departure_date' => 'required|date',
        'departure_time_h' => 'required|numeric|min:0|max:23',
        'departure_time_m' => 'required|numeric|min:0|max:59',
        'estimated_return_time_h' => 'required|numeric|min:0|max:23',
        'estimated_return_time_m' => 'required|numeric|min:0|max:59',
        'departure_location' => 'required|string',
        'destination' => 'required|string',
    ];

    protected $messages = [
        'vehicle_id.required' => 'Wybierz pojazd z listy.',
        'employee_id.required' => 'Wybierz kierowcę.',
        'departure_date.required' => 'Wprowadź datę wyjazdu.',
        'departure_time_h.required' => 'Wybierz godzinę.',
        'departure_time_m.required' => 'Wybierz minuty.',
        'estimated_return_time_h.required' => 'Wybierz godzinę.',
        'estimated_return_time_m.required' => 'Wybierz minuty.',
        'departure_location.required' => 'Pole skąd jest wymagane.',
        'destination.required' => 'Pole cel jest wymagane.',
        'return_date.required' => 'Wprowadź datę powrotu.',
        'return_time_h.required' => 'Wybierz godzinę powrotu.',
        'return_time_m.required' => 'Wybierz minuty powrotu.',
    ];

    public function selectEmployee($id, $name)
    {
        $this->employee_id = $id;
        $this->selectedEmployeeName = $name;
        $this->searchEmployee = ''; // Wyczyść pole wyszukiwania po wyborze
    }

    public function selectDeparture($loc)
    {
        $this->departure_location = $loc;
    }

    public function selectDestination($dest)
    {
        $this->destination = $dest;
    }

    public function startTrip()
    {
        $this->validate();

        // Sprawdź czy pojazd nie jest już w trasie
        $activeTrip = VehicleTrip::where('vehicle_id', $this->vehicle_id)->where('status', 'in_progress')->exists();
        if ($activeTrip) {
            session()->flash('error', 'Ten pojazd jest już w trasie!');
            return;
        }

        VehicleTrip::create([
            'vehicle_id' => $this->vehicle_id,
            'employee_id' => $this->employee_id,
            'departure_date' => $this->departure_date,
            'departure_time' => sprintf('%02d:%02d', $this->departure_time_h, $this->departure_time_m),
            'estimated_return_time' => sprintf('%02d:%02d', $this->estimated_return_time_h, $this->estimated_return_time_m),
            'departure_location' => $this->departure_location,
            'destination' => $this->destination,
            'status' => 'in_progress'
        ]);

        $this->reset([
            'vehicle_id', 'employee_id', 'selectedEmployeeName', 'searchEmployee', 
            'departure_time_h', 'departure_time_m', 
            'estimated_return_time_h', 'estimated_return_time_m', 
            'departure_location', 'destination'
        ]);
        $this->departure_date = date('Y-m-d');

        session()->flash('success', 'Wyjazd został zarejestrowany!');
        $this->dispatch('close-modal-start');
    }

    public function openEndTrip($id)
    {
        $this->end_trip_id = $id;
        $this->return_date = date('Y-m-d');
        $this->return_time_h = date('H');
        $this->return_time_m = date('i'); // or round to nearest 5
    }

    public function endTrip()
    {
        $this->validate([
            'return_date' => 'required|date',
            'return_time_h' => 'required|numeric|min:0|max:23',
            'return_time_m' => 'required|numeric|min:0|max:59',
        ]);

        $trip = VehicleTrip::findOrFail($this->end_trip_id);
        $trip->update([
            'return_date' => $this->return_date,
            'return_time' => sprintf('%02d:%02d', $this->return_time_h, $this->return_time_m),
            'status' => 'completed'
        ]);

        $this->reset(['end_trip_id', 'return_date', 'return_time_h', 'return_time_m']);
        session()->flash('success', 'Wyjazd został zakończony!');
        $this->dispatch('close-modal-end');
    }

    public function deleteTrip($id)
    {
        VehicleTrip::findOrFail($id)->delete();
        session()->flash('success', 'Wyjazd został usunięty z historii.');
    }

    public function render()
    {
        $kierowcaPosition = Position::where('name', 'Kierowca')->first();
        $drivers = collect();
        $others = collect();

        if (strlen($this->searchEmployee) > 0) {
            $query = Employee::where(function($q) {
                $q->where('first_name', 'like', '%' . $this->searchEmployee . '%')
                  ->orWhere('last_name', 'like', '%' . $this->searchEmployee . '%');
            })->orderBy('last_name');

            if ($kierowcaPosition) {
                $drivers = (clone $query)->where('position_id', $kierowcaPosition->id)->take(5)->get();
                $others = (clone $query)->where(function($q) use ($kierowcaPosition) {
                    $q->where('position_id', '!=', $kierowcaPosition->id)
                      ->orWhereNull('position_id');
                })->take(5)->get();
            } else {
                $others = $query->take(10)->get();
            }
        }

        $pastLocs = collect();
        $pastDests = collect();

        if (strlen($this->departure_location) > 0) {
            $pastLocs = VehicleTrip::select('departure_location')
                ->where('departure_location', 'like', '%' . $this->departure_location . '%')
                ->distinct()->pluck('departure_location');
        }

        if (strlen($this->destination) > 0) {
            $pastDests = VehicleTrip::select('destination')
                ->where('destination', 'like', '%' . $this->destination . '%')
                ->distinct()->pluck('destination');
        }

        return view('livewire.vehicles.trip-list', [
            'trips' => VehicleTrip::with(['vehicle', 'employee'])->orderBy('created_at', 'desc')->get(),
            'vehicles' => Vehicle::whereDoesntHave('trips', function($query) {
                $query->where('status', 'in_progress');
            })->get(), // Tylko dostępne pojazdy
            'driversList' => $drivers,
            'othersList' => $others,
            'pastLocations' => $pastLocs,
            'pastDestinations' => $pastDests,
        ]);
    }
}
