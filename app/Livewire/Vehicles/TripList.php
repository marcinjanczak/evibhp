<?php

namespace App\Livewire\Vehicles;

use Livewire\Component;
use App\Models\VehicleTrip;
use App\Models\Vehicle;
use App\Models\Employee;
use App\Models\Position;
use Livewire\Attributes\On;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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
    public $remarks;

    // Export trips
    public $export_vehicle_id = '';
    public $export_month = '';

    public function mount()
    {
        $this->departure_date = date('Y-m-d');
        $this->export_month = date('Y-m');
    }

    // End Trip
    public $end_trip_id;
    public $return_date;
    public $return_time_h;
    public $return_time_m;
    public $end_remarks;

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
            'remarks' => $this->remarks,
            'status' => 'in_progress'
        ]);

        $this->reset([
            'vehicle_id', 'employee_id', 'selectedEmployeeName', 'searchEmployee', 
            'departure_time_h', 'departure_time_m', 
            'estimated_return_time_h', 'estimated_return_time_m', 
            'departure_location', 'destination', 'remarks'
        ]);
        $this->departure_date = date('Y-m-d');

        session()->flash('success', 'Wyjazd został zarejestrowany!');
        $this->dispatch('close-modal-start');
        $this->dispatch('trip-updated');
    }

    public function openEndTrip($id)
    {
        $this->end_trip_id = $id;
        $this->return_date = date('Y-m-d');
        $this->return_time_h = date('H');
        $this->return_time_m = date('i'); // or round to nearest 5
        
        $trip = VehicleTrip::findOrFail($id);
        $this->end_remarks = $trip->remarks;
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
            'remarks' => $this->end_remarks,
            'status' => 'completed'
        ]);

        $this->reset(['end_trip_id', 'return_date', 'return_time_h', 'return_time_m', 'end_remarks']);
        session()->flash('success', 'Wyjazd został zakończony!');
        $this->dispatch('close-modal-end');
        $this->dispatch('trip-updated');
    }

    public function deleteTrip($id)
    {
        VehicleTrip::findOrFail($id)->delete();
        session()->flash('success', 'Wyjazd został usunięty z historii.');
        $this->dispatch('trip-updated');
    }

    public function exportTrips()
    {
        $this->validate([
            'export_vehicle_id' => 'required|exists:vehicles,id',
            'export_month' => 'required|date_format:Y-m',
        ], [
            'export_vehicle_id.required' => 'Wybierz pojazd z listy.',
            'export_month.required' => 'Wybierz miesiąc i rok.',
        ]);

        $vehicle = Vehicle::findOrFail($this->export_vehicle_id);
        $month = explode('-', $this->export_month)[1];
        $year = explode('-', $this->export_month)[0];

        $trips = VehicleTrip::where('vehicle_id', $vehicle->id)
            ->whereYear('departure_date', $year)
            ->whereMonth('departure_date', $month)
            ->orderBy('departure_date', 'asc')
            ->orderBy('departure_time', 'asc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Wyjazdy');

        $spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);

        $logoPath = public_path('images/planteon_logo.png');
        if (file_exists($logoPath)) {
            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath($logoPath);
            $drawing->setHeight(60);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(10);
            $drawing->setWorksheet($sheet);
        }

        $sheet->setCellValue('D1', 'LISTA UŻYTKOWANIA POJAZDU SŁUŻBOWEGO');
        $sheet->getStyle('D1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('D1:G1');

        $monthsPl = [
            '01' => 'STYCZEŃ', '02' => 'LUTY', '03' => 'MARZEC', '04' => 'KWIECIEŃ',
            '05' => 'MAJ', '06' => 'CZERWIEC', '07' => 'LIPIEC', '08' => 'SIERPIEŃ',
            '09' => 'WRZESIEŃ', '10' => 'PAŹDZIERNIK', '11' => 'LISTOPAD', '12' => 'GRUDZIEŃ',
        ];
        $monthName = $monthsPl[$month] ?? $month;
        $monthDisplay = $monthName . ' ' . $year;

        $sheet->setCellValue('D2', 'MIESIĄC: ' . $monthDisplay);
        $sheet->getStyle('D2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('D2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->mergeCells('D2:G2');

        // Dane pojazdu po prawej
        $sheet->setCellValue('H1', 'Marka pojazdu:');
        $sheet->setCellValue('I1', $vehicle->make);
        $sheet->getStyle('I1')->getFont()->setBold(true);

        $sheet->setCellValue('H2', 'Numer rejestracyjny pojazdu:');
        $sheet->setCellValue('I2', $vehicle->license_plate);
        $sheet->getStyle('I2')->getFont()->setBold(true);

        // Wysokość wierszy nagłówkowych
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(30);

        // --- NAGŁÓWKI TABELI ---
        $headers = [
            'A' => 'Nr wpisu',
            'B' => 'Data wyjazdu',
            'C' => 'Godzina wyjazdu',
            'D' => 'Przewidywana godzina powrotu',
            'E' => 'Opis trasy wyjazdu (skąd - dokąd)',
            'F' => 'Cel wyjazdu',
            'G' => "Podpis czytelny\nosoby pobierającej",
            'H' => 'Data powrotu',
            'I' => 'Godzina powrotu',
            'J' => "Podpis czytelny osoby\nzdającej klucze",
            'K' => 'Uwagi',
        ];

        $headerRow = 4;
        foreach ($headers as $col => $title) {
            $sheet->setCellValue($col . $headerRow, $title);
        }

        // Styl dla nagłówków tabeli
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FF000000'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FFE67E22'], // Pomarańczowy ze zdjęcia
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $sheet->getStyle('A4:K4')->applyFromArray($headerStyle);
        $sheet->getRowDimension(4)->setRowHeight(40); // Wyższy wiersz nagłówka dla zawijania tekstu

        // Szerokości kolumn
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(35);
        $sheet->getColumnDimension('F')->setWidth(25);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getColumnDimension('I')->setWidth(12);
        $sheet->getColumnDimension('J')->setWidth(25);
        $sheet->getColumnDimension('K')->setWidth(20);

        // --- UZUPEŁNIANIE DANYCH ---
        $rowNum = 5;
        foreach ($trips as $index => $trip) {
            $sheet->setCellValue('A' . $rowNum, $index + 1);
            $sheet->setCellValue('B' . $rowNum, $trip->departure_date);
            $sheet->setCellValue('C' . $rowNum, $trip->departure_time);
            $sheet->setCellValue('D' . $rowNum, $trip->estimated_return_time);
            $sheet->setCellValue('E' . $rowNum, $trip->departure_location . ' - ' . $trip->destination);
            $sheet->setCellValue('F' . $rowNum, $trip->destination);
            $sheet->setCellValue('G' . $rowNum, '');
            $sheet->setCellValue('H' . $rowNum, $trip->return_date ?? ''); // Brak godziny, sama data
            $sheet->setCellValue('I' . $rowNum, $trip->return_time ?? '');
            $sheet->setCellValue('J' . $rowNum, '');
            $sheet->setCellValue('K' . $rowNum, $trip->remarks ?? '');
            
            $sheet->getRowDimension($rowNum)->setRowHeight(30); // Wyższe rzędy na podpis
            $rowNum++;
        }

        // Jeśli brak tras, dodajmy ze 2 puste wiersze do wypisania
        if ($trips->isEmpty()) {
            for ($i = 0; $i < 3; $i++) {
                $sheet->getRowDimension($rowNum)->setRowHeight(30);
                $rowNum++;
            }
        }

        // Styl dla wierszy danych (wyśrodkowanie i krawędzie)
        $dataStyle = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        // Zastosowanie stylu od wiersza 5 do ostatniego wygenerowanego
        $lastRow = $rowNum - 1;
        if ($lastRow >= 5) {
            $sheet->getStyle('A5:K' . $lastRow)->applyFromArray($dataStyle);
        }

        $safePlate = preg_replace('/[^A-Za-z0-9_\-]/', '_', $vehicle->license_plate);
        $filename = "Wyjazdy_{$safePlate}_{$this->export_month}.xlsx";

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    #[On('vehicle-updated')]
    public function refreshTrips() {}

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
