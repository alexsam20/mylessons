<?php

namespace Framework\Http;
class Request
{
    private array $queryParams;
    private mixed $parsedBody = null;

    public function __construct(array $queryParams = [], $parsedBody = null) {
        $this->parsedBody = $parsedBody;
        $this->queryParams = $queryParams;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): self
    {
        $new = clone $this;
        $this->queryParams = $query;
        return $new;
    }

    public function getParsedBody(): ?array
    {
        return $this->parsedBody;
    }

    public function withParsedBody(?array $data): self
    {
        $new = clone $this;
        $this->parsedBody = $data;
        return $new;
    }
}
