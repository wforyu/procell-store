<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_id',
        'status',
        'total_amount',
        'shipping_address',
        'notes',
        'payment_method',
        'payment_proof',
        'payment_verified_at',
        'courier',
        'courier_service',
        'shipping_cost',
        'tracking_number',
        'shipped_at',
        'coupon_id',
        'discount_amount',
        'points_used',
        'points_discount',
        'points_earned',
        'midtrans_transaction_id',
        'midtrans_payment_type',
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'points_discount' => 'integer',
            'payment_verified_at' => 'datetime',
            'shipped_at' => 'datetime',
            'received_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function returns()
    {
        return $this->hasMany(Returns::class);
    }

    public function getGrandTotalAttribute()
    {
        return ($this->total_amount + $this->shipping_cost) - $this->discount_amount - $this->points_discount;
    }

    public function getSubTotalAttribute()
    {
        return $this->total_amount - $this->discount_amount - $this->points_discount;
    }
}
