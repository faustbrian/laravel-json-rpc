<?php

declare(strict_types=1);

namespace Tests;

use BombenProdukt\PackagePowerPack\TestBench\AbstractPackageTestCase;
use Illuminate\Bus\BusServiceProvider;

/**
 * @internal
 */
abstract class TestCase extends AbstractPackageTestCase
{
    protected function getRequiredServiceProviders(): array
    {
        return [
            BusServiceProvider::class,
        ];
    }

    protected function getServiceProviderClass(): string
    {
        return \BombenProdukt\JsonRpc\ServiceProvider::class;
    }
}
