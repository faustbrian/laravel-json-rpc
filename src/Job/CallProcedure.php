<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Job;

use BombenProdukt\JsonRpc\Exception\InternalErrorException;
use BombenProdukt\JsonRpc\Exception\InvalidParamsException;
use BombenProdukt\JsonRpc\Exception\RequestExceptionInterface;
use BombenProdukt\JsonRpc\Model\RequestObject;
use BombenProdukt\JsonRpc\Model\Response;
use BombenProdukt\JsonRpc\Procedure\ProcedureInterface;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use ReflectionClass;
use ReflectionParameter;
use Throwable;

final class CallProcedure
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        private readonly ProcedureInterface $procedure,
        private readonly Request $request,
        private readonly RequestObject $requestObject,
    ) {}

    public function handle(): Response
    {
        try {
            $result = App::call(
                [$this->procedure, 'handle'],
                [
                    'request' => $this->request,
                    'requestObject' => $this->requestObject,
                    ...$this->resolveParameters(
                        $this->procedure,
                        $this->requestObject->getParams(),
                    ),
                ],
            );

            return new Response(
                $this->requestObject->getJsonrpc(),
                $this->requestObject->getId(),
                $result,
            );
        } catch (Throwable $exception) {
            if ($exception instanceof ValidationException) {
                return new Response(
                    jsonrpc: '2.0',
                    id: $this->requestObject->getId(),
                    error: InvalidParamsException::fromValidationException($exception)->toError(),
                    result: null,
                );
            }

            if ($exception instanceof RequestExceptionInterface) {
                return new Response(
                    jsonrpc: '2.0',
                    id: $this->requestObject->getId(),
                    error: $exception->toError(),
                    result: null,
                );
            }

            return new Response(
                jsonrpc: '2.0',
                id: $this->requestObject->getId(),
                result: null,
                error: InternalErrorException::fromThrowable($exception)->toError(),
            );
        }
    }

    public function resolveParameters(ProcedureInterface $procedure, ?array $params): array
    {
        if (empty($params)) {
            return [];
        }

        $class = new ReflectionClass($procedure);

        return collect($class->getMethod('handle')->getParameters())
            ->map(fn (ReflectionParameter $parameter) => $parameter->getName())
            ->mapWithKeys(function (string $key) use ($params) {
                $value = Arr::get($params, $key);
                $valueDot = Arr::get($params, Str::snake($key, '.'));

                return [$key => $value ?? $valueDot];
            })
            ->filter()
            ->toArray();
    }
}
