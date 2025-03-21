<?php

namespace Framework\Http\Router;

class Result
{
    public function __construct(
        private readonly string $name,
        private                 $handler,
        private readonly array $attributes = []
    ) { }

    /**
     * @return string
     */
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

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
