<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $fillable = [
        'user_id',
        'points',
        'lifetime_points',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addPoints(int $points, string $type, ?string $referenceType = null, ?int $referenceId = null, ?string $description = null): static
    {
        $this->increment('points', $points);
        $this->increment('lifetime_points', $points);

        $this->transactions()->create([
            'user_id' => $this->user_id,
            'points' => $points,
            'type' => $type,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
        ]);

        return $this;
    }

    public function deductPoints(int $points, string $type, ?string $referenceType = null, ?int $referenceId = null, ?string $description = null): static
    {
        $this->decrement('points', $points);

        $this->transactions()->create([
            'user_id' => $this->user_id,
            'points' => -$points,
            'type' => $type,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'description' => $description,
        ]);

        return $this;
    }

    public function transactions()
    {
        return $this->hasMany(LoyaltyPointTransaction::class, 'user_id', 'user_id');
    }
}
