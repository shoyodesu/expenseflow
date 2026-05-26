<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::where('user_id', Auth::id());

        // Search
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Date range filter
        if ($request->filled('range')) {
            match ($request->range) {
                'this_month' => $query->whereMonth('expense_date', now()->month)
                                      ->whereYear('expense_date', now()->year),
                'last_3'     => $query->where('expense_date', '>=', now()->subMonths(3)),
                default      => null,
            };
        }

        $expenses = $query->orderByDesc('expense_date')->paginate(10)->withQueryString();
        $categories = Expense::categories();

        return view('expenses.index', compact('expenses', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description'  => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'category'     => 'required|in:' . implode(',', Expense::categories()),
            'expense_date' => 'required|date',
            'status'       => 'required|in:paid,pending',
            'notes'        => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();
        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('toast_success', 'Expense added successfully!');
    }

    public function edit(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        $categories = Expense::categories();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'description'  => 'required|string|max:255',
            'amount'       => 'required|numeric|min:0.01',
            'category'     => 'required|in:' . implode(',', Expense::categories()),
            'expense_date' => 'required|date',
            'status'       => 'required|in:paid,pending',
            'notes'        => 'nullable|string|max:1000',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('toast_success', 'Expense updated successfully!');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->user_id !== Auth::id()) {
            abort(403);
        }
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('toast_success', 'Expense deleted successfully!');
    }
}
