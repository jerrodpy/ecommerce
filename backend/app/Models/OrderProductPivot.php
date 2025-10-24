<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProductPivot extends Pivot
{
    public const string TABLE_NAME = 'order_product';

    public const string COLUMN_CART_ID = 'order_id';
    public const string COLUMN_PRODUCT_ID = 'product_id';
    public const string COLUMN_QUANTITY = 'quantity';
    public const string COLUMN_PRICE = 'price';

    protected $table = self::TABLE_NAME;
}
