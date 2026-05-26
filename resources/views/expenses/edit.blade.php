@extends('layouts.app')
@section('title', 'Edit Expense')
@section('page-title', 'Edit Expense')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="stat-card">
            <h5 class="fw-bold mb-4" style="font-family:'Syne',sans-serif">
                <i class="bi bi-pencil-square me-2" style="color:var(--yellow)"></i>Edit Expense
            </h5>

            @if($errors->any())
            <div class="alert alert-danger py-2 mb-3" style="font-size:13px;border-radius:8px">
                <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="{{ route('expenses.update', $expense) }}">
                @csrf @method('PUT')
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Description *</label>
                        <input type="text" name="description" class="form-control"
                               value="{{ old('description', $expense->description) }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Amount (₱) *</label>
                        <input type="number" name="amount" class="form-control"
                               step="0.01" min="0.01"
                               value="{{ old('amount', $expense->amount) }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Category *</label>
                        <select name="category" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}"
                                    {{ old('category', $expense->category) == $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Date *</label>
                        <input type="date" name="expense_date" class="form-control"
                               value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="paid"    {{ old('status', $expense->status) == 'paid'    ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ old('status', $expense->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes (optional)</label>
                        <textarea name="notes" class="form-control" rows="3">{{ old('notes', $expense->notes) }}</textarea>
                    </div>
                </div>
                <div class="d-flex gap-2 mt-4">
                    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-yellow px-4">
                        <i class="bi bi-save me-1"></i> Update Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
