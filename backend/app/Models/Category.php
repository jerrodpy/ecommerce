<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public const string TABLE_NAME = 'categories';

    public const string COLUMN_ID = 'id';

    public const string COLUMN_TITLE = 'title';

    public const string RELATION_PRODUCTS = 'products';

    public const null CREATED_AT = null;

    public const null UPDATED_AT = null;

    protected $fillable = [
        self::COLUMN_TITLE,
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
