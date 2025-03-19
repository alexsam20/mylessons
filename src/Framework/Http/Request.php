<?php

namespace Framework\Http;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class Request implements ServerRequestInterface
{
    public function __construct(private array $queryParams = [], private mixed $parsedBody = null) { }

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

    public function withParsedBody($data): self
    {
        $new = clone $this;
        $this->parsedBody = $data;
        return $new;
    }

    public function getProtocolVersion(): string {}
    public function withProtocolVersion(string $version): MessageInterface {}
    public function getHeaders(): array {}
    public function hasHeader(string $name): bool {}
    public function getHeader(string $name): array {}
    public function getHeaderLine(string $name): string {}
    public function withHeader(string $name, $value): MessageInterface {}
    public function withAddedHeader(string $name, $value): MessageInterface {}
    public function withoutHeader(string $name): MessageInterface {}
    public function getBody(): StreamInterface {}
    public function withBody(StreamInterface $body): MessageInterface {}
    public function getRequestTarget(): string {}
    public function withRequestTarget(string $requestTarget): RequestInterface {}
    public function getMethod(): string {}
    public function withMethod(string $method): RequestInterface {}
    public function getUri(): UriInterface {}
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface {}
    public function getServerParams(): array {}
    public function getCookieParams(): array {}
    public function withCookieParams(array $cookies): ServerRequestInterface {}
    public function getUploadedFiles(): array {}
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface {}
    public function getAttributes(): array {}
    public function getAttribute(string $name, $default = null) {}
    public function withAttribute(string $name, $value): ServerRequestInterface {}
    public function withoutAttribute(string $name): ServerRequestInterface {}
}
