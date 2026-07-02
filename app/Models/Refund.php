<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'refund_number',
        'order_id',
        'user_id',
        'amount',
        'reason',
        'description',
        'method',
        'status',
        'processed_by',
        'notes',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'processed_at' => 'datetime',
        ];
    }

    public static function booted(): void
    {
        static::creating(function (Refund $refund) {
            if (empty($refund->refund_number)) {
                $date = now()->format('Ymd');
                $last = static::whereDate('created_at', today())->count();
                $refund->refund_number = 'RF-'.$date.'-'.str_pad($last + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
