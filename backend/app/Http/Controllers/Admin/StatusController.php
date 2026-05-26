<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Status;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Base\ListWithPaginationRequest;
use App\Http\Resource\Admin\StatusResource;
use Illuminate\Http\JsonResponse;

class StatusController extends BaseController
{
    public function index(ListWithPaginationRequest $request): JsonResponse
    {
        $this->setData(StatusResource::collection(Status::cases()));

        return $this->sendResponse();
    }
}
