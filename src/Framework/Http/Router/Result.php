<?php

namespace Framework\Http\Router;

class Result
{
    public function __construct(
        private readonly string $name,
        private                 $handler,
        private readonly array $attributes = []
    ) { }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getHandler(): mixed
    {
        return $this->handler;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
