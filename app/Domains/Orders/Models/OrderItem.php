<?php

namespace App\Domains\Orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'unit_price' => 'integer',
            'line_total' => 'integer',
            'line_no' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
