<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ServerRequestInterface;
use function is_string;

class MiddlewareResolver
{
    /**
     * @param $handler
     * @return callable
     */
    public static function resolve($handler): callable
    {
        if(is_string($handler)) {
            return static function (ServerRequestInterface $request, callable $next) use ($handler) {
                $object = new $handler();
                return $object($request, $next);
            };
        }

        return $handler;
    }
}
