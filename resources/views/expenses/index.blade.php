@extends('layouts.app')
@section('title', 'My Expenses')
@section('page-title', 'My Expenses')

@section('topbar-actions')
    <button class="btn btn-yellow btn-sm" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
        <i class="bi bi-plus-lg me-1"></i> Add Expense
    </button>
@endsection

@section('content')

{{-- Filters --}}
<form method="GET" class="row g-2 mb-4" id="filterForm">
    <div class="col-12 col-md-5">
        <input type="text" name="search" class="form-control" placeholder="Search descriptions…"
               value="{{ request('search') }}">
    </div>
    <div class="col-6 col-md-3">
        <select name="category" class="form-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-6 col-md-2">
        <select name="range" class="form-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">All Time</option>
            <option value="this_month" {{ request('range') == 'this_month' ? 'selected' : '' }}>This Month</option>
            <option value="last_3"     {{ request('range') == 'last_3'     ? 'selected' : '' }}>Last 3 Months</option>
        </select>
    </div>
    <div class="col-6 col-md-2">
        <button type="submit" class="btn btn-yellow w-100">Filter</button>
    </div>
</form>

{{-- Table --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th><th>Description</th><th>Category</th>
                    <th>Amount</th><th>Date</th><th>Status</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                <tr>
                    <td class="text-muted" style="font-size:12px">{{ $loop->iteration }}</td>
                    <td>
                        <div style="font-weight:500">{{ $expense->description }}</div>
                        @if($expense->notes)
                            <small class="text-muted">{{ Str::limit($expense->notes, 40) }}</small>
                        @endif
                    </td>
                    <td>
                        <span class="badge-category cat-{{ strtolower($expense->category) }}">
                            {{ $expense->category }}
                        </span>
                    </td>
                    <td class="fw-bold" style="color:#E05050">₱{{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                    <td>
                        <span class="badge-category badge-{{ $expense->status }}">{{ ucfirst($expense->status) }}</span>
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('expenses.edit', $expense) }}"
                               class="btn btn-sm" style="background:#E0EEFF;color:#4A8FE0;font-size:12px">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form id="del-exp-{{ $expense->id }}" method="POST"
                                  action="{{ route('expenses.destroy', $expense) }}">
                                @csrf @method('DELETE')
                                <button type="button" class="btn btn-sm"
                                        style="background:#FFE5E5;color:#E05050;font-size:12px"
                                        onclick="confirmDelete('del-exp-{{ $expense->id }}')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-receipt fs-2 d-block mb-2"></i>
                        No expenses found. Click <strong>+ Add Expense</strong> to get started.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($expenses->hasPages())
    <div class="p-3 d-flex justify-content-between align-items-center border-top">
        <small class="text-muted">Showing {{ $expenses->firstItem() }}–{{ $expenses->lastItem() }} of {{ $expenses->total() }}</small>
        {{ $expenses->links() }}
    </div>
    @endif
</div>

{{-- Add Expense Modal --}}
<div class="modal fade" id="addExpenseModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:14px;overflow:hidden">
            <div class="modal-header" style="background:var(--yellow);border:none">
                <h5 class="modal-title fw-bold" style="font-family:'Syne',sans-serif;color:#1A1A2E">
                    <i class="bi bi-plus-circle me-2"></i>Add New Expense
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('expenses.store') }}">
                @csrf
                <div class="modal-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger py-2 mb-3" style="font-size:13px;border-radius:8px">
                        <ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Description *</label>
                            <input type="text" name="description" class="form-control"
                                   placeholder="e.g. SM Grocery run" value="{{ old('description') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Amount (₱) *</label>
                            <input type="number" name="amount" class="form-control"
                                   placeholder="0.00" step="0.01" min="0.01" value="{{ old('amount') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Category *</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select…</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Date *</label>
                            <input type="date" name="expense_date" class="form-control"
                                   value="{{ old('expense_date', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Status *</label>
                            <select name="status" class="form-select" required>
                                <option value="paid"    {{ old('status') == 'paid'    ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes (optional)</label>
                            <textarea name="notes" class="form-control" rows="2"
                                      placeholder="Any additional notes…">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-yellow px-4">
                        <i class="bi bi-save me-1"></i> Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Auto-open modal if there are validation errors (for the add form)
    @if($errors->any())
        var modal = new bootstrap.Modal(document.getElementById('addExpenseModal'));
        modal.show();
    @endif
</script>
@endpush
