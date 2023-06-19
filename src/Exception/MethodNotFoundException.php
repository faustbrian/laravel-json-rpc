<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Exception;

final class MethodNotFoundException extends AbstractRequestException
{
    public function __construct(mixed $data = null)
    {
        parent::__construct(
            errorCode: -32601,
            errorMessage: 'Method not found',
            errorData: $data,
        );
    }
}
