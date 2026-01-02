@extends('layout')

@section('content')
<div class="row">
    <!-- Purchase Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">Stock In (Purchase)</div>
            <div class="card-body">
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>ISBN</label>
                        <input type="text" name="isbn" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Book Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Author</label>
                        <input type="text" name="author" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="1" required>
                    </div>
                    <div class="mb-3">
                        <label>Unit Price</label>
                        <input type="number" name="price" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-success w-100">Add to Inventory</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Inventory List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Current Inventory</div>
            <div class="card-body">

            <!-- Search Bar -->
<form action="{{ route('inventory.index') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by Title, ISBN, or Author" value="{{ request('search') }}">
        <button class="btn btn-secondary" type="submit">Search</button>
    </div>
</form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>ISBN</th>
                            <th>Total Copies</th>
                            <th>Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($books as $book)
                        <tr>
                            <td><a href="{{ route('inventory.show', $book->id) }}">{{ $book->title }}</a></td>
                            <td>{{ $book->isbn }}</td>
                            <td>{{ $book->copies->count() }}</td>
                            <td>{{ $book->copies->where('status', 'available')->count() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection