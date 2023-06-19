<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Client;

use BombenProdukt\JsonRpc\Model\Error;
use BombenProdukt\JsonRpc\Model\RequestObject;
use BombenProdukt\JsonRpc\Model\Response;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Support\Facades\Http;

final class Client
{
    private PendingRequest $client;

    private array $batch = [];

    public function __construct(string $host)
    {
        $this->client = Http::baseUrl($host)->asJson();
    }

    public static function make(string $host): self
    {
        return new self($host);
    }

    public function add(RequestObject $request): self
    {
        $this->batch = $request->jsonSerialize();

        return $this;
    }

    /**
     * @param list<RequestObject> $requests
     */
    public function addMany(array $requests): self
    {
        foreach ($requests as $request) {
            $this->add($request);
        }

        return $this;
    }

    public function request(): Response
    {
        return $this->transform(
            $this->client->post(
                '/',
                \count($this->batch) === 1 ? $this->batch[0] : $this->batch,
            ),
        );
    }

    private function transform(ClientResponse $response): Response
    {
        if ($response->json('error') !== null) {
            return new Response(
                jsonrpc: $response->json('jsonrpc'),
                id: $response->json('id'),
                result: null,
                error: new Error(
                    code: $response->json('error.code'),
                    message: $response->json('error.message'),
                    data: $response->json('error.data'),
                ),
            );
        }

        return new Response(
            jsonrpc: $response->json('jsonrpc'),
            id: $response->json('id'),
            result: $response->json('result'),
            error: null,
        );
    }
}
