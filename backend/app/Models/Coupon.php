<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'restaurant_id', 'code', 'type', 'value', 'min_subtotal', 'usage_limit', 'used_count', 'starts_at', 'ends_at'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime'
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function usages(): HasMany
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function isActive(): bool
    {
        $now = Carbon::now();
        return (!$this->starts_at || $this->starts_at <= $now)
            && (!$this->ends_at || $this->ends_at >= $now)
            && ($this->usage_limit === null || $this->used_count < $this->usage_limit);
    }
}
