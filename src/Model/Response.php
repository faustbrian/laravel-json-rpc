<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Model;

use BombenProdukt\JsonRpc\Exception\RequestExceptionInterface;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

final readonly class Response implements Arrayable, JsonSerializable
{
    public function __construct(
        private string $jsonrpc,
        private mixed $id,
        private mixed $result,
        private ?Error $error = null,
    ) {
        //
    }

    public static function fromRequestException(RequestExceptionInterface $exception): self
    {
        return new self(
            jsonrpc: '2.0',
            id: null,
            result: null,
            error: $exception->toError(),
        );
    }

    public static function asNotification(): self
    {
        return new self(
            jsonrpc: '2.0',
            id: null,
            result: null,
        );
    }

    public function getJsonRpc(): string
    {
        return $this->jsonrpc;
    }

    public function getId(): mixed
    {
        return $this->id;
    }

    public function getResult(): mixed
    {
        return $this->result;
    }

    public function getError(): ?Error
    {
        return $this->error;
    }

    public function successful(): bool
    {
        return $this->error === null;
    }

    public function failed(): bool
    {
        return $this->error !== null;
    }

    public function isNotification(): bool
    {
        return $this->id === null && $this->result === null && $this->error === null;
    }

    public function toArray(): array
    {
        return [
            'jsonrpc' => $this->jsonrpc,
            'id' => $this->id,
            'result' => $this->result,
            'error' => $this->error,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
