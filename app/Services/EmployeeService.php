<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class EmployeeService 
{
    public function getPaginatedList(string $search = '', int $perPage = 10)
    {
        return Employee::query()
            ->with('position') 
            ->when($search, function ($query, $search) {
                $query->where('last_name', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%");
            })
            ->orderBy('last_name', 'asc')
            ->paginate($perPage);
    }

    public function createEmployee(array $data): Employee
    {
        return DB::transaction(function () use ($data) {
            return Employee::create([
                'first_name'  => $data['first_name'],
                'last_name'   => $data['last_name'],
                'position_id' => $data['position_id'] ?? null, 
            ]);
        });
    }
}