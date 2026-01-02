@extends('layout')

@section('content')
<div class="row text-center">
    <div class="col-md-12 mb-4">
        <h1>Library Dashboard</h1>
    </div>
    
    <!-- Total Books Card -->
    <div class="col-md-6">
        <div class="card text-white bg-success mb-3">
            <div class="card-header">Total Physical Books</div>
            <div class="card-body">
                <h1 class="card-title">{{ $totalBooks }}</h1>
                <p class="card-text">Copies currently in the database.</p>
            </div>
        </div>
    </div>

    <!-- Borrowed Books Card -->
    <div class="col-md-6">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header">Books Currently Borrowed</div>
            <div class="card-body">
                <h1 class="card-title">{{ $borrowedBooks }}</h1>
                <p class="card-text">Copies out with students.</p>
            </div>
        </div>
    </div>
</div>
@endsection