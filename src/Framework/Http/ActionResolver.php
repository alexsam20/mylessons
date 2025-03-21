<?php

namespace Framework\Http;

use function is_string;

class ActionResolver
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
