<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Request;

use BombenProdukt\JsonRpc\Exception\InvalidRequestException;
use BombenProdukt\JsonRpc\Exception\ParseErrorException;
use BombenProdukt\JsonRpc\Model\Request;
use Illuminate\Support\Arr;
use Throwable;

final class RequestParser implements RequestParserInterface
{
    public function parse(string $json): Request
    {
        try {
            $requestObjects = \json_decode($json, true, 512, \JSON_THROW_ON_ERROR);
        } catch (Throwable) {
            throw new ParseErrorException();
        }

        if (empty($requestObjects)) {
            throw new InvalidRequestException();
        }

        if (Arr::isAssoc($requestObjects)) {
            return Request::fromRequestObject($requestObjects);
        }

        return Request::fromRequestObjectBatch($requestObjects);
    }
}
