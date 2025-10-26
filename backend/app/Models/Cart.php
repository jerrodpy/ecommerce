<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    public const string TABLE_NAME = 'cart';

    public const string COLUMN_ID = 'id';

    public const string COLUMN_GUEST_ID = 'guest_id';

    public const string COLUMN_USER_ID = 'user_id';

    public const string RELATION_PRODUCTS = 'products';

    protected $fillable = [
        self::COLUMN_GUEST_ID,
        self::COLUMN_USER_ID,
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(CartProductPivot::COLUMN_QUANTITY)
            ->withTimestamps();
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
