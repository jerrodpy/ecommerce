<?php

namespace App\Http\Resource\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            Product::COLUMN_ID => $this->{Product::COLUMN_ID},
            Product::COLUMN_TITLE => $this->{Product::COLUMN_TITLE},
            Product::COLUMN_DESCRIPTION => $this->{Product::COLUMN_DESCRIPTION},
            Product::COLUMN_PRICE => $this->{Product::COLUMN_PRICE},
            Product::COLUMN_IMAGE => $this->{Product::COLUMN_IMAGE}
                ? Storage::disk('public')->url($this->{Product::COLUMN_IMAGE})
                : null,
            Product::RELATION_CATEGORIES => CategoryResource::collection($this->{Product::RELATION_CATEGORIES}),
        ];
    }
}
