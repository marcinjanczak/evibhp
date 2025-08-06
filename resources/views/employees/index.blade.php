@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista pracowników</h1>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Imię</th>
                <th>Nazwisko</th>
                <th>Akcje</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td>{{ $employee['id'] }}</td>
                <td>{{ $employee['first_name'] }}</td>
                <td>{{ $employee['last_name'] }}</td>
                <td>
                    <a href="#" class="btn btn-sm btn-primary">Edytuj</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection