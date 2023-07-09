---
title: Procedures
description: Learn how to create and register procedures.
breadcrumbs: [Documentation, The Basics, Procedures]
---

Procedures form the backbone of your JSON-RPC API, serving the critical role of handling requests. You have the liberty to create as many procedures as needed, yet each procedure can only be registered once per server per version. This implies that you can have a v1 server with a subtract procedure and a v2 server with a subtract procedure. However, it's not possible to have a v1 server featuring two subtract procedures unless they represent different versions.

## Creating A Procedure With Positional Parameters (Full Example)

```php
<?php

declare(strict_types=1);

namespace App\Http\Procedure;

use BombenProdukt\JsonRpc\Model\RequestObject;
use BombenProdukt\JsonRpc\Procedure\AbstractProcedure;

final class Subtract extends AbstractProcedure
{
    public function handle(RequestObject $requestObject): int
    {
        return $requestObject->getParams()[0] - $requestObject->getParams()[1];
    }

    // This can be omitted if you want to use the default version.
    public function getVersion(): string
    {
        return '1.0.0';
    }
}
```

## Creating A Procedure With Positional Parameters (Slim Example)

```php
<?php

declare(strict_types=1);

namespace App\Http\Procedure;

use BombenProdukt\JsonRpc\Model\RequestObject;
use BombenProdukt\JsonRpc\Procedure\AbstractProcedure;

final class Subtract extends AbstractProcedure
{
    public function handle(RequestObject $requestObject): int
    {
        return $requestObject->getParams()[0] - $requestObject->getParams()[1];
    }
}
```

## Creating A Procedure With Named Parameters (Full Example)

```php
<?php

declare(strict_types=1);

namespace App\Http\Procedure;

use BombenProdukt\JsonRpc\Procedure\AbstractProcedure;

final class SubtractWithBinding extends AbstractProcedure
{
    public function handle(string $minuend, string $subtrahend): int
    {
        return $minuend - $subtrahend;
    }

    // This can be omitted if you want to use the default version.
    public function getVersion(): string
    {
        return '1.0.0';
    }
}
```

## Creating A Procedure With Named Parameters (Slim Example)

```php
<?php

declare(strict_types=1);

namespace App\Http\Procedure;

use BombenProdukt\JsonRpc\Procedure\AbstractProcedure;

final class SubtractWithBinding extends AbstractProcedure
{
    public function handle(string $minuend, string $subtrahend): int
    {
        return $minuend - $subtrahend;
    }
}
```

## Versions

If you wish to request a specific version of a procedure, you have the option to do so by including the `X-JSON-RPC-Procedure-Version` header in your request. In cases where this header is absent, the request handler will default to utilizing the standard procedure. This default procedure can be identified by returning `true` from the `isDefault` method linked with the procedure.

Such an approach grants you the flexibility of maintaining a procedure with multiple versions for backwards compatibility, while also possessing a default procedure which can be called upon without necessitating the `X-JSON-RPC-Procedure-Version` header. Additionally, this strategy facilitates specifying the default version of a procedure based on certain business logic, instead of relying solely on a simple boolean flag.
