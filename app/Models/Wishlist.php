<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    protected $fillable = [
        'user_id',
        'item_name',
        'price',
        'priority',
        'description',
        'url',
        'purchased',
        'purchased_date'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'purchased' => 'boolean',
        'purchased_date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
