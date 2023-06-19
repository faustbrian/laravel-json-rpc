<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Exception;

final class ParseErrorException extends AbstractRequestException
{
    public function __construct(mixed $data = null)
    {
        parent::__construct(
            errorCode: -32700,
            errorMessage: 'Parse error',
            errorData: $data,
        );
    }
}
