---
title: Validation
description: Learn how to validate your JSON-RPC requests.
breadcrumbs: [Documentation, Digging Deeper, Validation]
---

Laravel provides several different approaches to validate your application's incoming data. It is most common to use the `validate` method available on all incoming HTTP requests. However, we will discuss other approaches to validation as well.

## Request Validation

If you want to validate your JSON-RPC requests, you can use the `validate` method available on all incoming HTTP requests. This method accepts an array of validation rules and will automatically return a JSON response with a 422 status code if the validation rules fail. If the validation rules pass, your code will keep executing normally.

```php
<?php

declare(strict_types=1);

namespace App\Http\Procedure;

use BombenProdukt\JsonRpc\Model\RequestObject;
use BombenProdukt\JsonRpc\Procedure\AbstractProcedure;
use Illuminate\Http\Request;

final class Subtract extends AbstractProcedure
{
    public function handle(Request $request, RequestObject $requestObject): int
    {
        $validated = $request->validate([
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);

        // The blog post is valid...

        return $requestObject->getParams()[0] - $requestObject->getParams()[1];
    }
}
```

## Form Request Validation

We recommend using [Form Request Validation](https://laravel.com/docs/8.x/validation#form-request-validation) when validating your JSON-RPC requests. This approach keeps your procedure clean and allows you to reuse your validation rules in other parts of your application. You also get access to the `authorize` method, which allows you to authorize the incoming request.

```php
<?php

declare(strict_types=1);

namespace App\Http\Procedure;

use App\Http\Requests\ExampleRequest;
use BombenProdukt\JsonRpc\Model\RequestObject;
use BombenProdukt\JsonRpc\Procedure\AbstractProcedure;
use Illuminate\Http\Request;

final class Subtract extends AbstractProcedure
{
    public function handle(ExampleRequest $request, RequestObject $requestObject): int
    {
        return $requestObject->getParams()[0] - $requestObject->getParams()[1];
    }
}
```
