<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Server;

use BombenProdukt\JsonRpc\Procedure\ProcedureRepository;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string              getEntryPoint()
 * @method static array               getMiddleware()
 * @method static ProcedureRepository getProcedureRepository()
 * @method static string              getVersion()
 */
final class Server extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ServerInterface::class;
    }
}
