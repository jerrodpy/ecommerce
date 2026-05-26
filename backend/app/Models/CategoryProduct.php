<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CategoryProduct extends Pivot
{
    public const string TABLE_NAME = 'category_product';

    public const string COLUMN_CATEGORY_ID = 'category_id';
    public const string COLUMN_PRODUCT_ID = 'product_id';

    protected $table = self::TABLE_NAME;
}
