<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Procedure;

use Illuminate\Support\Str;

abstract class AbstractProcedure implements ProcedureInterface
{
    public function getMethod(): string
    {
        return Str::snake(class_basename(static::class));
    }

    public function getVersion(): string
    {
        return '1.0.0';
    }
}
