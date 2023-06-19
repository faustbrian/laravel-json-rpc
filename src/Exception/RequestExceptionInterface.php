<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Exception;

use BombenProdukt\JsonRpc\Model\Error;

interface RequestExceptionInterface
{
    public function toError(): Error;

    public function toArray(): array;
}
