<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'description', 'amount',
        'category', 'expense_date', 'status', 'notes',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function categories(): array
    {
        return ['Food', 'Transport', 'Utilities', 'Entertainment', 'Health', 'Others'];
    }
}
