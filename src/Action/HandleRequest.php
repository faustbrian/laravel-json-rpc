<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Action;

use BombenProdukt\JsonRpc\Exception\RequestExceptionInterface;
use BombenProdukt\JsonRpc\Job\CallProcedure;
use BombenProdukt\JsonRpc\Model\RequestObject;
use BombenProdukt\JsonRpc\Model\Response;
use BombenProdukt\JsonRpc\Server\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Throwable;

final class HandleRequest
{
    public function __construct(
        private readonly ParseRequestBody $parseRequestBody,
        private readonly ValidateRequestBody $validateRequestBody,
    ) {}

    public function execute(Request $request)
    {
        try {
            $requestBody = $this->parseRequestBody->execute($request->getContent());

            $responses = collect($requestBody->getRequestObjects())
                ->map(function (mixed $requestObject) use ($request): mixed {
                    try {
                        $this->validateRequestBody->execute($requestObject);

                        $requestObject = RequestObject::fromArray($requestObject);

                        $procedure = Server::getProcedureRepository()->get(
                            $requestObject->getMethod(),
                            $request->header('X-JSON-RPC-Procedure-Version', '1.0.0'),
                        );

                        if ($requestObject->isNotification()) {
                            CallProcedure::dispatchAfterResponse($procedure, $request, $requestObject);

                            return Response::asNotification();
                        }

                        return (new CallProcedure($procedure, $request, $requestObject))->handle();
                    } catch (Throwable $exception) {
                        if ($exception instanceof RequestExceptionInterface) {
                            return new Response(
                                jsonrpc: '2.0',
                                id: $requestObject instanceof RequestObject ? $requestObject->getId() : Arr::get($requestObject, 'id'),
                                error: $exception->toError(),
                                result: null,
                            );
                        }

                        throw $exception;
                    }
                })
                ->reject(fn (Response $requestObject) => $requestObject->isNotification())
                ->values();

            if ($responses->isEmpty()) {
                return [];
            }

            if ($requestBody->isBatch()) {
                return $responses;
            }

            return $responses->first();
        } catch (Throwable $exception) {
            if ($exception instanceof RequestExceptionInterface) {
                return Response::fromRequestException($exception);
            }

            throw $exception;
        }
    }
}
