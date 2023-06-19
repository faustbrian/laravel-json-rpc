<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Exception;

final class InvalidRequestException extends AbstractRequestException
{
    public function __construct(mixed $data = null)
    {
        parent::__construct(
            errorCode: -32600,
            errorMessage: 'Invalid Request',
            errorData: $data,
        );
    }
}
