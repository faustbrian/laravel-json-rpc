<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Model;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
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

    public static function asRequest(string $method, ?array $params = null, mixed $id = null): self
    {
        return new self('2.0', $method, $params, $id ?? Str::uuid());
    }

    public static function asNotification(string $method, ?array $params = null): self
    {
        return new self('2.0', $method, $params, null);
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
