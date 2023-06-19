<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Http\Controller;

use BombenProdukt\JsonRpc\Action\HandleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;

final class ProcedureController extends Controller
{
    public function __invoke(Request $request, HandleRequest $handler): JsonResponse
    {
        /**
         * @var array<string, string> $headers
         */
        $headers = $request->headers->all();

        return Response::json($handler->execute($request->getContent(), $headers));
    }
}
