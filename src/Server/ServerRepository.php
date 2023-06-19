<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Server;

use BombenProdukt\JsonRpc\Exception\ServerNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

final class ServerRepository
{
    private Collection $servers;

    public function __construct(array $servers)
    {
        $this->servers = new Collection();

        foreach ($servers as $server) {
            $this->register($server);
        }
    }

    /**
     * @return Collection<int, ServerInterface>
     */
    public function all(): Collection
    {
        return $this->servers;
    }

    public function findByVersion(string $version): ServerInterface
    {
        return $this->servers->get($version, fn () => throw new ServerNotFoundException());
    }

    public function findByVersionHeader(Request $request): ServerInterface
    {
        return $this->findByVersion($request->header('X-JSON-RPC-Server-Version', '1.0.0'));
    }

    public function register(string|ServerInterface $server): void
    {
        if (\is_string($server)) {
            $server = App::make($server);
        }

        $this->servers[$server->getVersion()] = $server;
    }
}
