<?php

namespace App\Http\Resource;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            Category::COLUMN_ID => $this->{Category::COLUMN_ID},
            Category::COLUMN_TITLE => $this->{Category::COLUMN_TITLE},
            'products_count' => $this->products_count ?? 0,
        ];
    }
}
