<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    public const string TABLE_NAME = 'orders';

    public const string COLUMN_ID = 'id';

    public const string COLUMN_CUSTOMER_FIO = 'customer_fio';

    public const string COLUMN_CUSTOMER_PHONE = 'customer_phone';

    public const string COLUMN_COMMENTS = 'comments';

    public const string COLUMN_STATUS = 'status';

    public const string COLUMN_USER_ID = 'user_id';

    public const string RELATION_PRODUCTS = 'products';

    protected $fillable = [
        self::COLUMN_CUSTOMER_FIO,
        self::COLUMN_CUSTOMER_PHONE,
        self::COLUMN_STATUS,
        self::COLUMN_COMMENTS,
        self::COLUMN_USER_ID,
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(OrderProductPivot::COLUMN_QUANTITY, OrderProductPivot::COLUMN_PRICE);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            self::COLUMN_STATUS => Status::class,
        ];
    }
}
