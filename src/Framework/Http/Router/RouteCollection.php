<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Route\RegexpRoute;
use Framework\Http\Router\Route\Route;

class RouteCollection
{
    /**
     * @var array
     */
    private array $routes = [];

    /**
     * @param Route $route
     * @return void
     */
    public function addRoute(Route $route): void
    {
        $this->routes[] = $route;
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $methods
     * @param array $tokens
     * @return void
     */
    public function add($name, $pattern, $handler, array $methods = [], array $tokens = []): void
    {
        $this->addRoute(new RegexpRoute($name, $pattern, $handler,  $methods, $tokens));
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     * @return void
     */
    public function any($name, $pattern, $handler, array $tokens = []): void
    {
        $this->addRoute(new RegexpRoute($name, $pattern, $handler,  [], $tokens));
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     * @return void
     */
    public function get($name, $pattern, $handler, array $tokens = []): void
    {
        $this->addRoute(new RegexpRoute($name, $pattern, $handler,  ['GET'], $tokens));
    }

    /**
     * @param $name
     * @param $pattern
     * @param $handler
     * @param array $tokens
     * @return void
     */
    public function post($name, $pattern, $handler, array $tokens = []): void
    {
        $this->addRoute(new RegexpRoute($name, $pattern, $handler,  ['POST'], $tokens));
    }

    /**
     * @return Route[]
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}
