<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@expenseflow.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'gender'   => 'Male',
            'address'  => '123 Rizal Street, Quezon City',
        ]);

        // Regular user
        $user = User::create([
            'name'     => 'Juan Dela Cruz',
            'email'    => 'juan@example.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'gender'   => 'Male',
            'phone'    => '09171234567',
        ]);

        // Sample expenses for admin
        $expenses = [
            ['description' => 'SM Grocery run',       'amount' => 1240.00, 'category' => 'Food',          'status' => 'paid',    'expense_date' => now()->subDays(2)],
            ['description' => 'Grab ride to office',   'amount' =>  180.00, 'category' => 'Transport',     'status' => 'paid',    'expense_date' => now()->subDays(3)],
            ['description' => 'Meralco bill',          'amount' => 2400.00, 'category' => 'Utilities',     'status' => 'paid',    'expense_date' => now()->subDays(7)],
            ['description' => 'Netflix subscription',  'amount' =>  549.00, 'category' => 'Entertainment', 'status' => 'paid',    'expense_date' => now()->subDays(1)],
            ['description' => 'Mercury Drug vitamins',  'amount' =>  320.00, 'category' => 'Health',        'status' => 'pending', 'expense_date' => now()->subDays(5)],
            ['description' => 'Rice and viands',       'amount' =>  850.00, 'category' => 'Food',          'status' => 'paid',    'expense_date' => now()->subMonth()->subDays(2)],
            ['description' => 'PLDT Home Fiber',       'amount' => 1699.00, 'category' => 'Utilities',     'status' => 'paid',    'expense_date' => now()->subMonth()->subDays(10)],
            ['description' => 'LRT/MRT load',         'amount' =>  500.00, 'category' => 'Transport',     'status' => 'paid',    'expense_date' => now()->subMonth()->subDays(1)],
        ];

        foreach ($expenses as $e) {
            Expense::create(array_merge($e, ['user_id' => $admin->id]));
        }

        // A few for juan
        Expense::create(['user_id' => $user->id, 'description' => 'Jollibee lunch', 'amount' => 220.00, 'category' => 'Food', 'status' => 'paid', 'expense_date' => now()->subDays(1)]);
        Expense::create(['user_id' => $user->id, 'description' => 'Angkas', 'amount' => 95.00, 'category' => 'Transport', 'status' => 'paid', 'expense_date' => now()->subDays(2)]);
    }
}
