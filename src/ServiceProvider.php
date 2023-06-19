<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc;

use BombenProdukt\JsonRpc\Mixin\RouteMixin;
use BombenProdukt\JsonRpc\Server\ServerRepository;
use BombenProdukt\PackagePowerPack\Package\AbstractServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

final class ServiceProvider extends AbstractServiceProvider
{
    public function packageRegistered(): void
    {
        $this->app->singleton(
            ServerRepository::class,
            fn (Application $app) => new ServerRepository($app->config->get('json-rpc.servers')),
        );
    }

    public function packageBooted(): void
    {
        Route::mixin(new RouteMixin());
    }
}
