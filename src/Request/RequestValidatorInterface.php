<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Request;

interface RequestValidatorInterface
{
    public function validate(mixed $data): void;
}
