<?php

namespace Framework\Http;

use Psr\Http\Message\StreamInterface;
use function mb_strlen;

class Stream implements StreamInterface
{
    public function __construct(private string $content = '') {}

    public function __toString(): string
    {
        return $this->getContents();
    }

    public function getContents(): string
    {
        return $this->content;
    }

    public function write(string $string): int
    {
        $this->content .= $string;
    }

    public function getSize(): ?int
    {
        return mb_strlen($this->content);
    }

    public function close(): void {}
    public function detach() {}
    public function tell(): int {}
    public function eof(): bool {}
    public function isSeekable(): bool {}
    public function seek(int $offset, int $whence = SEEK_SET): void {}
    public function rewind(): void {}
    public function isWritable(): bool {}
    public function isReadable(): bool {}
    public function read(int $length): string {}
    public function getMetadata(?string $key = null) {}
}
