<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Expense extends Model
{
    use HasFactory;

    /**
     * The single source of truth for expense categories.
     * Using this constant across controllers and views ensures consistency.
     */
    public const CATEGORIES = [
        'Utilities',
        'Rent',
        'Salaries',
        'Office Supplies',
        'Transport',
        'Marketing',
        'Other'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'vendor',
        'amount',
        'expense_date',
        'category',
        'status',
        'receipt_path',
        'is_tax_deductible',
        'is_recurring',
        'description'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'is_tax_deductible' => 'boolean',
        'is_recurring' => 'boolean'
    ];

    /**
     * Get the user that owns the expense.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include expenses that are due.
     */
    public function scopeBillsDue($query)
    {
        return $query->where('status', 'due');
    }

    /**
     * Get the full URL for the receipt.
     */
    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt_path ? Storage::url($this->receipt_path) : null;
    }
}
