<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Request;

use BombenProdukt\JsonRpc\Model\Request;

interface RequestParserInterface
{
    public function parse(string $json): Request;
}
