<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ── Stat cards ─────────────────────────────────
        $totalExpenses    = Expense::where('user_id', $user->id)->sum('amount');
        $monthlyExpenses  = Expense::where('user_id', $user->id)
            ->whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');
        $totalUsers       = User::count();
        $totalCategories  = Expense::where('user_id', $user->id)
            ->distinct('category')->count('category');

        // ── Monthly bar chart (last 12 months) ─────────
        $monthlyData = Expense::where('user_id', $user->id)
            ->where('expense_date', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw('MONTH(expense_date) as month'),
                DB::raw('YEAR(expense_date) as year'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get();

        $months = [];
        $amounts = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            $match = $monthlyData->first(fn($r) => $r->month == $date->month && $r->year == $date->year);
            $amounts[] = $match ? (float)$match->total : 0;
        }

        // ── Category donut ──────────────────────────────
        $categoryData = Expense::where('user_id', $user->id)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        // ── Recent expenses ─────────────────────────────
        $recentExpenses = Expense::where('user_id', $user->id)
            ->orderByDesc('expense_date')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalExpenses', 'monthlyExpenses', 'totalUsers',
            'totalCategories', 'months', 'amounts',
            'categoryData', 'recentExpenses'
        ));
    }
}
