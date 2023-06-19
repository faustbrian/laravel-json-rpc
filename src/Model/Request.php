<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Model;

final readonly class Request
{
    public function __construct(
        private array $requestObjects,
        private bool $isBatch,
    ) {
        //
    }

    public static function fromRequestObject(array $requestObject): self
    {
        return new self([$requestObject], false);
    }

    public static function fromRequestObjectBatch(array $requestObjects): self
    {
        return new self($requestObjects, true);
    }

    public function getRequestObjects(): array
    {
        return $this->requestObjects;
    }

    public function isBatch(): bool
    {
        return $this->isBatch;
    }
}
