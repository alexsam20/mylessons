<?php

namespace Framework\Http;
class Request
{
    public function __construct(private array $queryParams = [], private mixed $parsedBody = null) { }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): Request|static
    {
        $new = clone $this;
        $this->queryParams = $query;
        return $new;
    }

    public function getParsedBody(): ?array
    {
        return $this->parsedBody;
    }

    public function withParsedBody(?array $data): Request|static
    {
        $new = clone $this;
        $this->parsedBody = $data;
        return $new;
    }
}
