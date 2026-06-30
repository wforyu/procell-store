<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReferralCode extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'total_referrals',
        'total_points_earned',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = 'REF-'.strtoupper(Str::random(6));
        } while (static::where('code', $code)->exists());

        return $code;
    }
}
