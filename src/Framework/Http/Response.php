<?php

namespace Framework\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    private array $headers = [];
    private StreamInterface $body;
    private int $statusCode;
    private string $reasonPhrase = '';

    private static array $phrases = [
        200 => 'OK',
        301 => 'Moved Permanently',
        400 => 'Bad Request',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    ];

    public function __construct($body, int $status = 200)
    {
        $this->body = $body instanceof StreamInterface ? $body : new Stream($body);
        $this->statusCode = $status;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): self
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getReasonPhrase(): string
    {
        if (!$this->reasonPhrase && isset(self::$phrases[$this->statusCode])) {
            $this->reasonPhrase = self::$phrases[$this->statusCode];
        }

        return $this->reasonPhrase;
    }

    public function withStatus($code, $reasonPhrase = ''): self
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase;

        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[$name]);
    }

    public function getHeader($name): array
    {
        if (!$this->hasHeader($name)) {
            return [];
        }
        return $this->headers[$name];
    }

    public function withHeader($name, $value): self
    {
        $new = clone $this;
        if ($new->hasHeader($name)) {
            unset($new->headers[$name]);
        }
        $new->headers[$name] = $value;

        return $new;
    }

    public function withAddedHeader(string $name, $value): MessageInterface
    {
        $new = clone $this;
        $new->headers[$name] = array_merge($new->headers[$name] ?? [], (array)$value);
        return $new;
    }

    public function withoutHeader(string $name): MessageInterface
    {
        $new = clone $this;
        if ($new->hasHeader($name)) {
            unset($new->headers[$name]);
        }
        return $new;
    }

    public function getProtocolVersion(): string {}
    public function withProtocolVersion(string $version): MessageInterface {}
    public function getHeaderLine(string $name): string {}
}
