<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    public const string RELATION_PRODUCTS = 'products';

    public const string TABLE_NAME = 'cart';

    public const string COLUMN_ID = 'id';

    public const string COLUMN_GUEST_ID = 'guest_id';

    protected $fillable = [
        self::COLUMN_GUEST_ID,
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(CartProductPivot::COLUMN_QUANTITY)
            ->withTimestamps();
    }
}
