<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Request;

use BombenProdukt\JsonRpc\Exception\RequestExceptionInterface;
use BombenProdukt\JsonRpc\Job\CallProcedure;
use BombenProdukt\JsonRpc\Model\RequestObject;
use BombenProdukt\JsonRpc\Model\Response;
use BombenProdukt\JsonRpc\Server\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Throwable;

final class RequestHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly RequestParserInterface $parser,
        private readonly RequestValidatorInterface $validator,
    ) {}

    public function handle(Request $request): Collection|Response
    {
        try {
            $requestBody = $this->parser->parse($request->getContent());

            $responses = collect($requestBody->getRequestObjects())
                ->map(function (mixed $requestObject) use ($request): mixed {
                    try {
                        $this->validator->validate($requestObject);

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
                return $responses;
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
