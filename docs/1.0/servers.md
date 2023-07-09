---
title: Servers
description: Learn how to create and register servers.
breadcrumbs: [Documentation, The Basics, Servers]
---

Servers act as the entry point for your JSON-RPC API. They shoulder the responsibility of registering procedures and managing requests. While you have the freedom to create as many servers as you desire, a particular procedure can only be registered once per server per version. For instance, you could have a v1 server with a subtract procedure and a v2 server with a subtract procedure. However, it is not permissible to have a v1 server with two subtract procedures unless these procedures belong to distinct versions.

## Creating a Server (Full Example)

```php
<?php

declare(strict_types=1);

namespace App\Http\Server;

use BombenProdukt\JsonRpc\Http\Middleware\BootServer;
use BombenProdukt\JsonRpc\Server\AbstractServer;

final class Server extends AbstractServer
{
    // This can be omitted if you want to use the default route path.
    public function getRoutePath(): string
    {
        return '/';
    }

    // This can be omitted if you want to use the default route name.
    public function getRouteName(): string
    {
        return 'json-rpc.v1';
    }

    // This can be omitted if you want to use the default version.
    public function getVersion(): string
    {
        return '1.0.0';
    }

    // This can be omitted if you want to use the default middleware.
    public function getMiddleware(): array
    {
        return [
            BootServer::class,
        ];
    }

    public function procedures(): array
    {
        return [
            \App\Http\Procedure\Subtract::class,
        ];
    }
}
```

## Creating a Server (Slim Example)

```php
<?php

declare(strict_types=1);

namespace App\Http\Server;

use BombenProdukt\JsonRpc\Http\Middleware\BootServer;
use BombenProdukt\JsonRpc\Server\AbstractServer;

final class Server extends AbstractServer
{
    public function procedures(): array
    {
        return [
            \App\Http\Procedure\Subtract::class,
        ];
    }
}
```

## Registering a Server

```php
Route::jsonRpc(\App\Http\Server\Server::class);
```

## Versions

If you wish to request a specific version of a server, you have the option to do so by including the `X-JSON-RPC-Server-Version` header in your request. In cases where this header is absent, the request handler will default to utilizing the standard server. This default server can be identified by returning `true` from the `isDefault` method linked with the server.

Such an approach grants you the flexibility of maintaining a server with multiple versions for backwards compatibility, while also possessing a default server which can be called upon without necessitating the `X-JSON-RPC-Server-Version` header. Additionally, this strategy facilitates specifying the default version of a server based on certain business logic, instead of relying solely on a simple boolean flag.
