---
title: Configuration
description: Laravel JSON-RPC offers extensive configuration options.
breadcrumbs: [Documentation, Configuration]
---

You may publish the configuration file using the following command:

```bash
php artisan vendor:publish --tag="laravel-json-rpc-config"
```

## JSON-RPC Server Namespace

This value sets the namespace for the JsonRpc part of your application. This is used whenever a JSON-RPC server class is referenced within the application.

```php
'namespace_server' => 'App\\Http\\Server',
```

## JSON-RPC Servers

Here you may define a list of servers for your application. Each server is represented by its fully qualified class name.

This value determines where all the JSON-RPC server classes are stored. These classes are used to handle incoming JSON-RPC requests and are automatically registered with the application.

```php
'servers' => [
    // \App\Http\Server\Server::class,
],
```
