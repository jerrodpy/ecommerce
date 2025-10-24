<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    public const string TABLE_NAME = 'product';

    public const string COLUMN_ID = 'id';
    public const string COLUMN_TITLE = 'title';
    public const string COLUMN_DESCRIPTION = 'description';
    public const string COLUMN_PRICE = 'price';
    public const string COLUMN_IMAGE = 'image';

    public const string RELATION_CATEGORIES = 'categories';
    public const string RELATION_ORDERS = 'orders';

    protected $fillable = [
        self::COLUMN_TITLE,
        self::COLUMN_DESCRIPTION,
        self::COLUMN_PRICE,
        self::COLUMN_IMAGE,
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
