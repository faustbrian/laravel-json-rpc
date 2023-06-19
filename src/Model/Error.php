<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Model;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

final readonly class Error implements Arrayable, JsonSerializable
{
    public function __construct(
        private int $code,
        private string $message,
        private mixed $data = null,
    ) {
        //
    }

    public static function fromArray(array $array): self
    {
        return new self(
            code: $array['code'],
            message: $array['message'],
            data: $array['data'] ?? null,
        );
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function toArray(): array
    {
        return collect([
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data,
        ])->filter()->toArray();
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
