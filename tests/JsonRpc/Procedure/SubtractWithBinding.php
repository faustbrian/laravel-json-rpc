<?php

declare(strict_types=1);

namespace Tests\JsonRpc\Procedure;

use BombenProdukt\JsonRpc\Procedure\AbstractProcedure;

final class SubtractWithBinding extends AbstractProcedure
{
    public function handle(string $minuend, string $subtrahend): int
    {
        return $minuend - $subtrahend;
    }
}
