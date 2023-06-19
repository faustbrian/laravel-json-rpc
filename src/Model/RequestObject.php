<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Model;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

final readonly class RequestObject implements Arrayable, JsonSerializable
{
    public function __construct(
        private string $jsonrpc,
        private string $method,
        private ?array $params,
        private mixed $id,
    ) {
        //
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['jsonrpc'],
            $data['method'],
            $data['params'] ?? null,
            $data['id'] ?? null,
        );
    }

    public function getJsonrpc(): string
    {
        return $this->jsonrpc;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParams(): ?array
    {
        return $this->params;
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function isNotification(): bool
    {
        return $this->id === null;
    }

    public function toArray(): array
    {
        return collect([
            'jsonrpc' => $this->jsonrpc,
            'id' => $this->id,
            'method' => $this->method,
            'params' => $this->params,
        ])->filter()->toArray();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
