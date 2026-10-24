<?php

namespace App\Domains\Orders\Models;

use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_confirmed' => 'boolean',
            'is_prepared' => 'boolean',
            'is_left_warehouse' => 'boolean',
            'is_shipped' => 'boolean',
            'detail_fetched' => 'boolean',
            'meta' => 'array',
            'amount' => 'integer',
            'shipping_amount' => 'integer',
            'total_amount' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
