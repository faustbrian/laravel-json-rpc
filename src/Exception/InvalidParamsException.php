<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Exception;

use Illuminate\Validation\ValidationException;

final class InvalidParamsException extends AbstractRequestException
{
    public static function fromValidationException(ValidationException $exception): self
    {
        return new self(
            errorCode: -32602,
            errorMessage: 'Invalid params',
            errorData: $exception->validator->errors()->toArray(),
        );
    }
}
