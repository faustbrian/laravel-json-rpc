<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Procedure;

use BombenProdukt\JsonRpc\Exception\DuplicateProcedureException;
use BombenProdukt\JsonRpc\Exception\MethodNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;

final class ProcedureRepository
{
    private array $procedures = [];

    public function __construct(array $procedures = [])
    {
        foreach ($procedures as $procedure) {
            $this->register($procedure);
        }
    }

    public function all(): array
    {
        return $this->procedures;
    }

    public function get(string $method, string $version): ProcedureInterface
    {
        $procedure = $this->procedures[$method][$version] ?? null;

        if ($procedure === null) {
            throw new MethodNotFoundException();
        }

        return $procedure;
    }

    public function register(string|ProcedureInterface $procedure): void
    {
        if (\is_string($procedure)) {
            $procedure = App::make($procedure);
        }

        $method = $procedure->getMethod();

        if (Arr::has($this->procedures, $method)) {
            throw new DuplicateProcedureException($procedure);
        }

        $this->procedures[$method][$procedure->getVersion()] = $procedure;
    }
}
