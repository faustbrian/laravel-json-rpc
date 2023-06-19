<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Server;

use BombenProdukt\JsonRpc\Procedure\ProcedureRepository;

interface ServerInterface
{
    public function getRoutePath(): string;

    public function getRouteName(): string;

    public function getVersion(): string;

    public function getMiddleware(): array;

    public function getProcedureRepository(): ProcedureRepository;
}
