<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
    ];

    protected $appends = [
        'total_spent',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function completedOrders()
    {
        return $this->hasMany(Order::class)->whereIn('status', ['completed', 'shipped']);
    }

    public function getTotalSpentAttribute()
    {
        return $this->completedOrders()->sum('total_amount');
    }

    public function getLastOrderDateAttribute()
    {
        return $this->orders()->latest('created_at')->value('created_at');
    }
}
