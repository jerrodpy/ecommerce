<?php

namespace App\Http\Resource\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->value,
            'name' => $this->name,
        ];
    }
}
