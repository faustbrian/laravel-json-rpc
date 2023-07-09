---
title: Requests
description: Learn how to send requests to your JSON-RPC API.
breadcrumbs: [Documentation, The Basics, Requests]
---

Once you have your JSON-RPC API up and running, you can start sending requests to it. This page will show you how to send requests to your JSON-RPC API. It will also show you how to handle the responses.

## JSON-RPC Client

### Usage

You can create a client by using the `BombenProdukt\JsonRpc\Client\Client` class.

```php
$client = Client::make('https://jsonrpc.example.com');
```

Once you have created a client, you can start adding requests to it.

```php
$client->add(RequestObject::make(Str::uuid(), 'subtract', [42, 23]));
```

If you want to add multiple requests at once, you can use the `addMany` method.

```php
$client->addMany([
    RequestObject::make(Str::uuid(), 'subtract', [42, 23]),
    RequestObject::make(Str::uuid(), 'subtract', [23, 42])
]);
```

After you have added all the requests you want to send, you can send them by using the `request` method.

```php
$responseObjects = $client->request();
```

Note that the `request` method will return an array of `BombenProdukt\JsonRpc\Client\ResponseObject` objects. Each response object will contain the response for the request that was sent.

### Request Object Helpers

The `BombenProdukt\JsonRpc\Model\RequestObject` class provides several helper methods that aid in the creation of request objects. Two of these methods are `asRequest` and `asNotification`.

The `asRequest` method crafts a request object, which expects a response from the server. On the other hand, the `asNotification` method generates a request object that does not anticipate a response from the server.

#### Request

The `asRequest` method accepts three parameters. The first parameter is the method name. The second parameter is the parameters that should be sent to the server. The third parameter is the ID of the request object and is optional.

```php
$client->add(RequestObject::asRequest('subtract', [42, 23]));
```

```php
$client->add(RequestObject::asRequest('subtract', [42, 23], 'id'));
```

#### Notification

The `asNotification` method accepts two parameters. The first parameter is the method name. The second parameter is the parameters that should be sent to the server.

```php
$client->add(RequestObject::asNotification('update', [1, 2, 3, 4, 5]));
```

## JSON-RPC Call Examples

### Syntax

```json
--> Data Sent To Server
<-- Data Sent To Client
```

### With Positional Parameters

```json
--> {"jsonrpc": "2.0", "method": "subtract", "params": [42, 23], "id": 1}
<-- {"jsonrpc": "2.0", "result": 19, "id": 1}
```

```json
--> {"jsonrpc": "2.0", "method": "subtract", "params": [23, 42], "id": 2}
<-- {"jsonrpc": "2.0", "result": -19, "id": 2}
```

### With Named Parameters

```json
--> {"jsonrpc": "2.0", "method": "subtract", "params": {"subtrahend": 23, "minuend": 42}, "id": 3}
<-- {"jsonrpc": "2.0", "result": 19, "id": 3}
```

```json
--> {"jsonrpc": "2.0", "method": "subtract", "params": {"minuend": 42, "subtrahend": 23}, "id": 4}
<-- {"jsonrpc": "2.0", "result": 19, "id": 4}
```

### A Notification

```json
--> {"jsonrpc": "2.0", "method": "update", "params": [1,2,3,4,5]}
```

```json
--> {"jsonrpc": "2.0", "method": "foobar"}
```

### Of Non Existent Method

```json
--> {"jsonrpc": "2.0", "method": "foobar", "id": "1"}
<-- {"jsonrpc": "2.0", "error": {"code": -32601, "message": "Method not found"}, "id": "1"}
```

### With Invalid Json

```json
--> {"jsonrpc": "2.0", "method": "foobar, "params": "bar", "baz]
<-- {"jsonrpc": "2.0", "error": {"code": -32700, "message": "Parse error"}, "id": null}
```

### With Invalid Request Object

```json
--> {"jsonrpc": "2.0", "method": 1, "params": "bar"}
<-- {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}
```

### Batch Invalid Json

```json
--> [
    {"jsonrpc": "2.0", "method": "sum", "params": [1,2,4], "id": "1"},
    {"jsonrpc": "2.0", "method"
]
<-- {"jsonrpc": "2.0", "error": {"code": -32700, "message": "Parse error"}, "id": null}
```

### With An Empty Array

```json
--> []
<-- {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}
```

### With An Invalid Batch But Not Empty

```json
--> [1]
<-- [
    {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}
]
```

### With Invalid Batch

```json
--> [1,2,3]
<-- [
    {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null},
    {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null},
    {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null}
]
```

### Batch

```json
--> [
{"jsonrpc": "2.0", "method": "sum", "params": [1,2,4], "id": "1"},
    {"jsonrpc": "2.0", "method": "notify_hello", "params": [7]},
    {"jsonrpc": "2.0", "method": "subtract", "params": [42,23], "id": "2"},
    {"foo": "boo"},
    {"jsonrpc": "2.0", "method": "foo.get", "params": {"name": "myself"}, "id": "5"},
    {"jsonrpc": "2.0", "method": "get_data", "id": "9"}
]
<-- [
    {"jsonrpc": "2.0", "result": 7, "id": "1"},
    {"jsonrpc": "2.0", "result": 19, "id": "2"},
    {"jsonrpc": "2.0", "error": {"code": -32600, "message": "Invalid Request"}, "id": null},
    {"jsonrpc": "2.0", "error": {"code": -32601, "message": "Method not found"}, "id": "5"},
    {"jsonrpc": "2.0", "result": ["hello", 5], "id": "9"}
]
```

### Batch All Notifications

```json
--> [
    {"jsonrpc": "2.0", "method": "notify_sum", "params": [1,2,4]},
    {"jsonrpc": "2.0", "method": "notify_hello", "params": [7]}
]
<-- //Nothing is returned for all notification batches
```
