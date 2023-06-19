<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Procedure;

interface ProcedureInterface
{
    public function getMethod(): string;

    public function getVersion(): string;
}
