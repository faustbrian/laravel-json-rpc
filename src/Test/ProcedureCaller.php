<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Test;

use Illuminate\Support\Facades\URL;
use function Pest\Laravel\call;

final class ProcedureCaller
{
    public static function call(string $path): void
    {
        $request = \file_get_contents(\realpath(__DIR__."/../../tests/Fixtures/Requests/{$path}.json"));
        $response = \file_get_contents(\realpath(__DIR__."/../../tests/Fixtures/Responses/{$path}.json"));

        call('POST', URL::to('/'), [], [], [], [], $request)
            ->assertOk()
            ->assertHeader('Content-Type', 'application/json')
            ->assertJson(\json_decode($response, true, 512, \JSON_THROW_ON_ERROR));
    }
}
