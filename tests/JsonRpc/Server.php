<?php

declare(strict_types=1);

namespace Tests\JsonRpc;

use BombenProdukt\JsonRpc\Server\AbstractServer;
use Tests\JsonRpc\Procedure\GetData;
use Tests\JsonRpc\Procedure\NotifyHello;
use Tests\JsonRpc\Procedure\NotifySum;
use Tests\JsonRpc\Procedure\Subtract;
use Tests\JsonRpc\Procedure\SubtractWithBinding;
use Tests\JsonRpc\Procedure\Sum;

final class Server extends AbstractServer
{
    public function procedures(): array
    {
        return [
            GetData::class,
            NotifyHello::class,
            NotifySum::class,
            Subtract::class,
            SubtractWithBinding::class,
            Sum::class,
        ];
    }
}
