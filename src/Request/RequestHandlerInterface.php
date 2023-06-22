<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Request;

use BombenProdukt\JsonRpc\Model\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface RequestHandlerInterface
{
    public function handle(Request $request): Collection|Response;
}
