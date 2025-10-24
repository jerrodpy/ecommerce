<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
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

    private ?string $errorType = null;

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

    public function setData(mixed $data): void
    {
        if ($data instanceof JsonResource) {
            $this->data = $data->resolve();
        } elseif ($data instanceof Arrayable) {
            $this->data = $data->toArray();
        } else {
            $this->data = $data;
        }
    }

    public function setMessage(string $message = ''): void
    {
        $this->message = $message;
    }

    /**
     * @param int $code
     *
     */
    public function setStatusCode($code): void
    {
        $this->statusCode = $code;
    }

    /**
     *
     * @internal param Validator $validator
     */
    public function setErrors(array $errors = []): void
    {
        $this->errors = $errors;
    }

    public function setResponseHeader(array $headers = []): void
    {
        $this->headers = $headers;
    }

    public function sendResponse(): JsonResponse
    {
        $code = $this->statusCode;

        return response()->json(
            [
                'data' => $this->data,
                'message' => $this->message,
                'success' => $this->statusCode >= 200 && $this->statusCode <= 299,
                'errors_bag' => [
                    'errors' => $this->errors,
                    'type' => $this->errorType,
                ],
                'code' => $code,
            ],
            $code,
            $this->headers
        );
    }

    public function sendJsonResponse(mixed $data = null, int $code = 200, array $headers = [], array $meta = []): JsonResponse
    {
        if ($data === null) {
            $data = [];
        }

        if ($data instanceof JsonResource) {
            $data = ['data' => $data->resolve()];
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
