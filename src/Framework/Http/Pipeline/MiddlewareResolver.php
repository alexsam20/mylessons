<?php

namespace Framework\Http\Pipeline;

use function is_string;

class MiddlewareResolver
{
    /**
     * @param $handler
     * @return callable
     */
    public static function resolve($handler): callable
    {
        return is_string($handler) ? new $handler() : $handler;
    }
}
