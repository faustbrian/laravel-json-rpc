<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Http\Middleware;

use BombenProdukt\JsonRpc\Server\ServerInterface;
use BombenProdukt\JsonRpc\Server\ServerRepository;
use Closure;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class BootServer
{
    public function __construct(
        private Container $container,
        private ServerRepository $serverRepository,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $this->container->instance(
            ServerInterface::class,
            $this->serverRepository->findByVersionHeader($request),
        );

        return $next($request);
    }

    public function terminate(): void
    {
        $this->container->forgetInstance(ServerInterface::class);
    }
}
