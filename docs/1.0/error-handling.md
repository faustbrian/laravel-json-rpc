---
title: Error Handling
description: Learn how to handle errors in your JSON-RPC API.
breadcrumbs: [Documentation, Digging Deeper, Error Handling]
---

Laravel provides several different approaches to handle errors in your application. We expose various exceptions as JSON-RPC errors. However, you may want to handle errors in your own way. In this chapter, we will discuss how to handle errors in your JSON-RPC API.

## Error Handling

If you want to handle errors in your JSON-RPC API, you should use a middleware and apply it to your server. We recommend to use an error handling middleware that is already available in Laravel. For example, you may use [reporting errors](https://laravel.com/docs/10.x/errors#reporting-errors) or [rendering errors](https://laravel.com/docs/10.x/errors#rendering-errors).

### Creating Exceptions

If you want to create your own exceptions, you should extend the `BombenProdukt\JsonRpc\Exception\AbstractRequestException` class. The class provides a constructor that accepts the following arguments:

- `int $errorCode` - The error code of the exception.
- `string $errorMessage` - The error message of the exception.
- `mixed $errorData` - The error data of the exception.

Any exceptions that are not an instance of `AbstractRequestException` will be converted to an internal server error. The internal server error will have the error code `-32603` and the error message `Internal error`. Exceptions that are an instance of `AbstractRequestException` will be converted to a JSON-RPC error response.

#### Example

```php
<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Exception;

final class MethodNotFoundException extends AbstractRequestException
{
    public function __construct(mixed $data = null)
    {
        parent::__construct(
            errorCode: -32601,
            errorMessage: 'Method not found',
            errorData: $data,
        );
    }
}
```

## Reserved Error Codes

The JSON-RPC specification reserves the following error codes:

- `-32700` - Parse error (Invalid JSON was received by the server. An error occurred on the server while parsing the JSON text.)
- `-32600` - Invalid Request (The JSON sent is not a valid Request object.)
- `-32601` - Method not found (The method does not exist / is not available.)
- `-32602` - Invalid params (Invalid method parameter(s).)
- `-32603` - Internal error (Internal JSON-RPC error.)
- `-32000` to `-32099` - Server error (Reserved for implementation-defined server-errors.)

The remainder of the space is available for application defined errors.
