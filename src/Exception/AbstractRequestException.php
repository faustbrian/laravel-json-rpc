<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Exception;

use BombenProdukt\JsonRpc\Model\Error;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

abstract class AbstractRequestException extends Exception implements RequestExceptionInterface
{
    public function __construct(
        private ?int $errorCode = null,
        private ?string $errorMessage = null,
        private mixed $errorData = [],
    ) {
        parent::__construct(
            $this->getErrorMessage(),
            $this->getErrorCode(),
        );
    }

    public function getErrorCode(): int
    {
        return $this->errorCode ?? $this->code;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage ?? $this->message;
    }

    public function getErrorData(): mixed
    {
        return $this->errorData;
    }

    public function toError(): Error
    {
        return Error::fromArray($this->toArray());
    }

    public function toArray(): array
    {
        $message = [
            'code' => $this->getErrorCode(),
            'message' => $this->getErrorMessage(),
            'data' => $this->getErrorData(),
        ];

        if (App::hasDebugModeEnabled()) {
            Arr::set(
                $message,
                'data.debug',
                [
                    'file' => $this->getFile(),
                    'line' => $this->getLine(),
                    'trace' => $this->getTraceAsString(),
                ],
            );
        }

        return $message;
    }
}
