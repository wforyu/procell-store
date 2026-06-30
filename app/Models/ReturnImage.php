<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnImage extends Model
{
    protected $fillable = [
        'return_id',
        'image',
    ];

    public function return()
    {
        return $this->belongsTo(Returns::class, 'return_id');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/'.$this->image) : null;
    }
}
