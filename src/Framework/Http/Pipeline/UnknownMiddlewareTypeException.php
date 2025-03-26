<?php

namespace Framework\Http\Pipeline;

use InvalidArgumentException;

class UnknownMiddlewareTypeException extends InvalidArgumentException
{

    public function __construct(private mixed $type)
    {
        parent::__construct('Unknown middleware type');
    }

    /**
     * @return mixed
     */
    public function getType(): mixed
    {
        return $this->type;
    }
}
