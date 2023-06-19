<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Server;

use BombenProdukt\JsonRpc\Procedure\ProcedureRepository;

interface ServerInterface
{
    public function getEntryPoint(): string;

    public function getVersion(): string;

    public function getMiddleware(): array;

    public function getProcedureRepository(): ProcedureRepository;
}
