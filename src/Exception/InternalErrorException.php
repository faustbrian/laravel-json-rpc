<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Exception;

use Throwable;

final class InternalErrorException extends AbstractRequestException
{
    public static function fromThrowable(Throwable $exception): self
    {
        return new self(
            errorCode: -32603,
            errorMessage: 'Internal error',
            errorData: [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
            ],
        );
    }
}
