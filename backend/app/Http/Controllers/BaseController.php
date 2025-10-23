<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseController
{
    use DispatchesJobs;
    use ValidatesRequests;

    protected const FIELD_RESULT = 'result';

    protected int $statusCode = Response::HTTP_OK;

    protected ?string $message = null;

    protected array $errors = [];

    protected array $data = [];

    protected array $headers = [];

    public function validate(Request $request, array $rules, array $messages): void
    {
        $validator = Validator::make(
            $request->toArray(),
            $rules,
            $messages
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    public function sendJsonResponse(mixed $data = null, int $code = 200, array $headers = [], array $meta = []): JsonResponse
    {
        if ($data === null) {
            $data = [];
        }

        if ($data instanceof Collection) {
            $data = ['data' => $data->map(fn (Arrayable $resource) => $resource->toArray())];
        }

        if ($data instanceof Arrayable) {
            $data = ['data' => $data->toArray()];
        }

        if (!Arr::exists($data, 'data')) {
            $data = array_merge($data, ['data' => []]);
        }

        $data = array_merge($data, $meta);

        Arr::set($data, 'message', $this->message);
        Arr::set($data, 'success', $code >= 200 && $code <= 299);
        Arr::set($data, 'errors_bag', [
            'errors' => $this->errors,
        ]);
        Arr::set($data, 'code', $code);

        $response = new JsonResponse($data, $code);

        if ($headers) {
            collect($headers)->each(
                function ($value, $header) use (&$response) {
                    $response->header($header, $value);
                }
            );
        }

        return $response;
    }
}
