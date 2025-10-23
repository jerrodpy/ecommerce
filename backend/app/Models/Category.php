<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public const null CREATED_AT = null;

    public const null UPDATED_AT = null;

    protected $fillable = [
        'description',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
