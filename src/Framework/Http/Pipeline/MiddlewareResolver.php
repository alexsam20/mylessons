<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ServerRequestInterface;
use function is_array;
use function is_string;

class MiddlewareResolver
{
    /**
     * @param $handler
     * @return callable
     */
    public function resolve($handler): callable
    {
        if (is_array($handler)) {
            return $this->createPipe($handler);
        }
        if(is_string($handler)) {
            return static function (ServerRequestInterface $request, callable $next) use ($handler) {
                $object = new $handler();
                return $object($request, $next);
            };
        }

        return $handler;
    }

    private function createPipe(array $handlers): Pipeline
    {
        $pipeline = new Pipeline();
        foreach ($handlers as $handler) {
            $pipeline->pipe($this->resolve($handler));
        }

        return $pipeline;
    }
}
