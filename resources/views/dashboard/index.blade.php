@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('topbar-actions')
    <a href="{{ route('expenses.index') }}" class="btn btn-yellow btn-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Expense
    </a>
@endsection

@section('content')

@php
    function abbreviate(float $n): string {
        if ($n >= 1_000_000) return '₱' . number_format($n / 1_000_000, 1) . 'M';
        if ($n >= 1_000)     return '₱' . number_format($n / 1_000, 1) . 'K';
        return '₱' . number_format($n, 2);
    }
@endphp

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:var(--yellow-light)">💸</div>
            <div class="stat-value" title="₱{{ number_format($totalExpenses, 2) }}">
                {{ abbreviate($totalExpenses) }}
            </div>
            <div class="stat-label mt-1">Total Expenses</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#D4EDDA">📅</div>
            <div class="stat-value" title="₱{{ number_format($monthlyExpenses, 2) }}">
                {{ abbreviate($monthlyExpenses) }}
            </div>
            <div class="stat-label mt-1">This Month</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#D1ECF1">👥</div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label mt-1">Total Users</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#F8D7DA">🏷️</div>
            <div class="stat-value">{{ $totalCategories }}</div>
            <div class="stat-label mt-1">Categories Used</div>
        </div>
    </div>
</div>

{{-- Charts Row --}}
<div class="row g-3 mb-4">
    {{-- Bar Chart --}}
    <div class="col-lg-8">
        <div class="stat-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0 fw-bold" style="font-size:14px">Monthly Expenses (Last 12 Months)</h6>
            </div>
            <canvas id="barChart" style="max-height:220px"></canvas>
        </div>
    </div>
    {{-- Donut Chart --}}
    <div class="col-lg-4">
        <div class="stat-card h-100">
            <h6 class="mb-3 fw-bold" style="font-size:14px">Expenses by Category</h6>
            <canvas id="donutChart" style="max-height:180px"></canvas>
            <div id="legendContainer" class="mt-3" style="font-size:12px"></div>
        </div>
    </div>
</div>

{{-- Recent Expenses --}}
<div class="table-card">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">Recent Expenses</h6>
        <a href="{{ route('expenses.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:12px">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Description</th><th>Category</th><th>Amount</th><th>Date</th><th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentExpenses as $expense)
                <tr>
                    <td>{{ $expense->description }}</td>
                    <td>
                        <span class="badge-category cat-{{ strtolower($expense->category) }}">
                            {{ $expense->category }}
                        </span>
                    </td>
                    <td class="fw-bold" style="color:#E05050">₱{{ number_format($expense->amount, 2) }}</td>
                    <td>{{ $expense->expense_date->format('M d, Y') }}</td>
                    <td>
                        <span class="badge-category badge-{{ $expense->status }}">
                            {{ ucfirst($expense->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="bi bi-receipt fs-3 d-block mb-2"></i>
                        No expenses yet. <a href="{{ route('expenses.index') }}">Add your first one!</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const yellow = '#F5C800';
const dark   = '#1A1A2E';

// ── Bar Chart ──────────────────────────────────────────────
const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Expenses (₱)',
            data: @json($amounts),
            backgroundColor: @json($amounts).map((v, i, a) =>
                v === Math.max(...a) ? yellow : 'rgba(245,200,0,.25)'
            ),
            borderColor: yellow,
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' ₱' + ctx.parsed.y.toLocaleString('en-PH', {minimumFractionDigits:2})
                }
            }
        },
        scales: {
            y: { ticks: { callback: v => '₱' + v.toLocaleString() }, grid: { color: '#F0F0F6' } },
            x: { grid: { display: false } }
        }
    }
});

// ── Donut Chart ────────────────────────────────────────────
const catLabels  = @json($categoryData->pluck('category'));
const catTotals  = @json($categoryData->pluck('total'));
const palette = [yellow, '#4A8FE0', '#3BBF7A', '#E05050', '#A855F7', '#F97316'];

const donutCtx = document.getElementById('donutChart').getContext('2d');
new Chart(donutCtx, {
    type: 'doughnut',
    data: {
        labels: catLabels,
        datasets: [{ data: catTotals, backgroundColor: palette, borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
        responsive: true,
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => ' ₱' + parseFloat(ctx.parsed).toLocaleString('en-PH', {minimumFractionDigits:2})
                }
            }
        }
    }
});

// Custom legend
const leg = document.getElementById('legendContainer');
catLabels.forEach((label, i) => {
    leg.innerHTML += `<div class="d-flex align-items-center gap-2 mb-1">
        <div style="width:10px;height:10px;border-radius:50%;background:${palette[i]};flex-shrink:0"></div>
        <span>${label}</span>
        <span class="ms-auto fw-bold">₱${parseFloat(catTotals[i]).toLocaleString('en-PH',{minimumFractionDigits:2})}</span>
    </div>`;
});
</script>
@endpush
