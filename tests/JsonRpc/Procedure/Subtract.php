<?php

declare(strict_types=1);

namespace Tests\JsonRpc\Procedure;

use BombenProdukt\JsonRpc\Model\RequestObject;
use BombenProdukt\JsonRpc\Procedure\AbstractProcedure;

final class Subtract extends AbstractProcedure
{
    public function handle(RequestObject $request): int
    {
        return $request->getParams()[0] - $request->getParams()[1];
    }
}
