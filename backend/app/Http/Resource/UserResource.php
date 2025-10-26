<?php

namespace App\Http\Resource;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            User::COLUMN_ID => $this->{User::COLUMN_ID},
            User::COLUMN_NAME => $this->{User::COLUMN_NAME},
            User::COLUMN_EMAIL => $this->{User::COLUMN_EMAIL},
            Model::UPDATED_AT => $this->{Model::UPDATED_AT},
            Model::CREATED_AT => $this->{Model::CREATED_AT},
        ];
    }
}
