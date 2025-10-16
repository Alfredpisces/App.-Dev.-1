<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model {
    use HasFactory;
    protected $fillable = ['user_id', 'customer_name', 'amount', 'sale_date', 'due_date', 'status', 'is_taxable', 'notes'];
    protected $casts = ['amount' => 'decimal:2', 'sale_date' => 'date', 'due_date' => 'date', 'is_taxable' => 'boolean'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function scopeOverdue($query) { return $query->where('status', 'sent')->where('due_date', '<', now()); }
    public function scopeOutstanding($query) { return $query->whereIn('status', ['sent', 'overdue']); }
    public function getIsOverdueAttribute(): bool { return $this->status === 'sent' && $this->due_date?->isPast(); }
}