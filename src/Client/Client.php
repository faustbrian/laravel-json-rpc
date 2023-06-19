<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Client;

use BombenProdukt\JsonRpc\Model\Error;
use BombenProdukt\JsonRpc\Model\RequestObject;
use BombenProdukt\JsonRpc\Model\Response;
use Illuminate\Http\Client\PendingRequest;
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

    /**
     * @return list<Response>|Response
     */
    public function request(): array|Response
    {
        $response = $this->client->post(
            '/',
            $this->isBatch() ? $this->batch : $this->batch[0],
        );

        if ($this->isBatch()) {
            return \array_map(
                fn (array $response) => $this->transform($response),
                $response->json(),
            );
        }

        return $this->transform($response->json());
    }

    private function transform(array $response): Response
    {
        if ($response['error'] !== null) {
            return new Response(
                jsonrpc: $response['jsonrpc'],
                id: $response['id'],
                result: null,
                error: new Error(
                    code: $response['error']['code'],
                    message: $response['error']['message'],
                    data: $response['error']['data'] ?? null,
                ),
            );
        }

        return new Response(
            jsonrpc: $response['jsonrpc'],
            id: $response['id'],
            result: $response['result'],
            error: null,
        );
    }

    private function isBatch(): bool
    {
        return \count($this->batch) > 1;
    }
}
