<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Exception;

use BombenProdukt\JsonRpc\Model\Error;
use Exception;

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
        return $this->errorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
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
        return collect([
            'code' => $this->getErrorCode(),
            'message' => $this->getErrorMessage(),
            'data' => $this->getErrorData(),
        ])->filter()->toArray();
    }
}
