---
title: Authentication
description: Learn how to authenticate your JSON-RPC requests.
breadcrumbs: [Documentation, Digging Deeper, Authentication]
---

Laravel provides several different approaches to authenticate your application's incoming data. It is most common to use the `auth` middleware available on all incoming HTTP requests. However, this approach is not suitable for JSON-RPC requests. Instead, we will discuss other approaches to authentication as well.

## Request Authentication

If you want to authenticate your JSON-RPC requests, you should use a middleware and apply it to your server. We recommend to use an authentication middleware that is already available in Laravel. For example, you may use [HTTP Basic Authentication](https://laravel.com/docs/10.x/authentication#http-basic-authentication) or [API Token Authentication](https://laravel.com/docs/10.x/sanctum#api-token-authentication).

### HTTP Basic Authentication

To authenticate your JSON-RPC requests using HTTP Basic Authentication, you should use the `auth.basic` middleware. You may assign the middleware to your server in the `getMiddleware` function:

```php
<?php

declare(strict_types=1);

namespace App\Http\Server;

use BombenProdukt\JsonRpc\Http\Middleware\BootServer;
use BombenProdukt\JsonRpc\Server\AbstractServer;

final class Server extends AbstractServer
{
    public function getMiddleware(): array
    {
        return array_merge(
            ['auth.basic'],
            parent::getMiddleware(),
        );
    }

    public function procedures(): array
    {
        return [
            \App\Http\Procedure\Subtract::class,
        ];
    }
}
```

### API Token Authentication

To authenticate your JSON-RPC requests using API Token Authentication, you should use the `auth:sanctum` middleware. You may assign the middleware to your server in the `getMiddleware` function:

```php
<?php

declare(strict_types=1);

namespace App\Http\Server;

use BombenProdukt\JsonRpc\Http\Middleware\BootServer;
use BombenProdukt\JsonRpc\Server\AbstractServer;

final class Server extends AbstractServer
{
    public function getMiddleware(): array
    {
        return array_merge(
            ['auth:sanctum'],
            parent::getMiddleware(),
        );
    }

    public function procedures(): array
    {
        return [
            \App\Http\Procedure\Subtract::class,
        ];
    }
}
```
