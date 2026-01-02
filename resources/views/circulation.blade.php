@extends('layout')

@section('content')
<div class="row">
    <!-- Borrow Form -->
    <div class="col-md-6">
        <div class="card border-primary mb-3">
            <div class="card-header bg-primary text-white">Borrow Book (Stock Out)</div>
            <div class="card-body">
                <form action="{{ route('circulation.borrow') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>User ID (Student)</label>
                        <input type="text" name="user_id" class="form-control" placeholder="Enter User ID" required>
                    </div>
                    <div class="mb-3">
                        <label>Book Accession # (Barcode)</label>
                        <input type="text" name="accession_number" class="form-control" placeholder="e.g. BK-123456" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Process Borrow</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Return Form -->
    <div class="col-md-6">
        <div class="card border-success mb-3">
            <div class="card-header bg-success text-white">Return Book (Stock In)</div>
            <div class="card-body">
                <form action="{{ route('circulation.return') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Book Accession # (Barcode)</label>
                        <input type="text" name="accession_number" class="form-control" placeholder="e.g. BK-123456" required>
                    </div>
                    <button type="submit" class="btn btn-success">Process Return</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Active Transactions List -->
    <div class="col-md-12 mt-4">
        <h3>Currently Borrowed Books</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Book Title</th>
                    <th>Accession #</th>
                    <th>Borrowed Date</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeTransactions as $trans)
                <tr>
                    <td>{{ $trans->user_id }}</td>
                    <td>{{ $trans->bookCopy->book->title }}</td>
                    <td>{{ $trans->bookCopy->accession_number }}</td>
                    <td>{{ $trans->borrowed_at }}</td>
                    <td class="text-danger">{{ $trans->due_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection