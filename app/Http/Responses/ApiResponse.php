<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class ApiResponse implements Responsable
{
    protected int $httpCode;
    protected $data;
    protected string $errorMessage;

    public function __construct(int $httpCode, $data = null, string $errorMessage = '')
    {
        $this->httpCode = $httpCode;
        $this->data = $data;
        $this->errorMessage = $errorMessage;
    }
    
    public function toResponse($request): \Illuminate\Http\JsonResponse
    {
        $payload = match (true) {
            $this->httpCode >= 500 => ['error_message' => 'Server error'],
            $this->httpCode >= 400 => ['error_message' => $this->errorMessage],
            $this->httpCode >= 200 => ['data' => $this->data],
        };

        return response()->json(
            data: $payload,
            status: $this->httpCode,
        );
    }

    public static function ok($data)
    {
        return new static(200, $data);
    }

    public static function created($data)
    {
        return new static(201, $data);
    }

    public static function notFound(string $errorMessage = "Item not found")
    {
        return new static(404, errorMessage: $errorMessage);
    }
}