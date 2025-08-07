@extends('layouts.app')

@section('content')

<main>
    <h1>Lista pracowników</h1>
 <table class="table table-hover">
        <thead class="bg-light">
          <tr>
            <th class="text-nowrap">ID</th>
            <th>Imię</th>
            <th>Nazwisko</th>
            <th class="text-end">Akcje</th>
          </tr>
        </thead>
        <tbody>
          @foreach($employees as $employee)
          <tr>
            <td class="fw-bold">{{ $employee->id }}</td>
            <td>{{ $employee->imie }}</td>
            <td>{{ $employee->nazwisko }}</td>
            <td class="text-end">
              <div class="d-flex gap-2 justify-content-end">
                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-outline-danger btn-sm" 
                          onclick="return confirm('Na pewno usunąć?')">
                    <i class="fas fa-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
</main>

@endsection