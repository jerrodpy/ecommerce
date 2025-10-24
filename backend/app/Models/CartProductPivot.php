<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CartProductPivot extends Pivot
{
    public const string TABLE_NAME = 'cart_product';

    public const string COLUMN_ID = 'id';
    public const string COLUMN_CART_ID = 'cart_id';
    public const string COLUMN_PRODUCT_ID = 'product_id';
    public const string COLUMN_QUANTITY = 'quantity';
    public const string COLUMN_PRICE = 'price';

    public $timestamps = true;

    protected $table = self::TABLE_NAME;
}
