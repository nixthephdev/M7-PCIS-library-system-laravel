@extends('layout')

@section('content')
<a href="{{ route('inventory.index') }}" class="btn btn-secondary mb-3">&larr; Back to Inventory</a>

<div class="card mb-4">
    <div class="card-body">
        <h2>{{ $book->title }}</h2>
        <p><strong>Author:</strong> {{ $book->author }} | <strong>ISBN:</strong> {{ $book->isbn }}</p>
    </div>
</div>

<h3>Physical Copies</h3>
<table class="table table-bordered bg-white">
    <thead>
        <tr>
            <th>Accession # (Barcode)</th>
            <th>Status</th>
            <th>Current Location / Borrower</th>
        </tr>
    </thead>
    <tbody>
        @foreach($book->copies as $copy)
        <tr>
            <td>{{ $copy->accession_number }}</td>
            <td>
                <span class="badge bg-{{ $copy->status == 'available' ? 'success' : 'danger' }}">
                    {{ ucfirst($copy->status) }}
                </span>
            </td>
            <td>
                @if($copy->status == 'borrowed')
                    <!-- Logic to find the active borrower -->
                    @php 
                        $activeTrans = $copy->borrowTransactions->whereNull('returned_at')->first();
                    @endphp
                    @if($activeTrans)
                        Borrowed by: <strong>{{ $activeTrans->user->name }} (ID: {{ $activeTrans->user->id }})</strong>
                    @endif
                @else
                    On Shelf
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection