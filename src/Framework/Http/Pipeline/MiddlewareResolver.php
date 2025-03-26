<?php

namespace Framework\Http\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use ReflectionObject;
use function count;
use function is_array;
use function is_object;
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
        if (is_string($handler)) {
            return function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler) {
                $middleware = $this->resolve(new $handler());
                return $middleware($request, $response, $next);
            };
        }

        if ($handler instanceof MiddlewareInterface) {
            return static function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler) {
                return $handler->process($request, new InteropHandlerWrapper($next));
            };
        }

        if (is_object($handler)) {
            $reflection = new ReflectionObject($handler);
            if ($reflection->hasMethod('__invoke')) {
                $method = $reflection->getMethod('__invoke');
                $parameters = $method->getParameters();
                if (count($parameters) === 2 && $parameters[1]->isCallable()) {
                    return static function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($handler) {
                        return $handler($request, $next);
                    };
                }
                return $handler;
            }
        }

        throw new UnknownMiddlewareTypeException($handler);

    }

    /**
     * @param array $handlers
     * @return Pipeline
     */
    private function createPipe(array $handlers): Pipeline
    {
        $pipeline = new Pipeline();
        foreach ($handlers as $handler) {
            $pipeline->pipe($this->resolve($handler));
        }

        return $pipeline;
    }
}
