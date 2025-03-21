<?php

namespace Tests\Framework\Http;

use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\RouteCollection;
use Framework\Http\Router\Router;
use InvalidArgumentException;
use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Uri;
use PHPUnit\Framework\TestCase;


class RouterTest extends TestCase
{
    public function testCorrectMethod(): void
    {
        $routes = new RouteCollection();

        $routes->get($nameGet = 'blog', '/blog', $handlerGet = 'handler_get');
        $routes->post($namePost = 'blog_edit', '/blog', $handlerPost = 'handler_post');

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog'));
        self::assertEquals($nameGet, $result->getName());
        self::assertEquals($handlerGet, $result->getHandler());

        $result = $router->match($this->buildRequest('POST', '/blog'));
        self::assertEquals($namePost, $result->getName());
        self::assertEquals($handlerPost, $result->getHandler());
    }

    public function testMissingMethod(): void
    {
        $routes = new RouteCollection();

        $routes->post('blog', '/blog', 'handler_post');

        $router = new Router($routes);

        $this->expectException(RequestNotMatchedException::class);
        $router->match($this->buildRequest('DELETE', '/blog'));
    }

    public function testCorrectAttributes(): void
    {
        $routes = new RouteCollection();

        $routes->get('blog_show', '/blog/{id}', 'handler', ['id' => '\d+']);

        $router = new Router($routes);

        $result = $router->match($this->buildRequest('GET', '/blog/5'));
        self::assertEquals('blog_show', $result->getName());
        self::assertEquals(['id' => '5'], $result->getAttributes());
    }

    public function testIncorrectAttributes(): void
    {
        $routes = new RouteCollection();

        $routes->get('blog_show', '/blog/{id}', 'handler', ['id' => '\d+']);

        $router = new Router($routes);

        $this->expectException(RequestNotMatchedException::class);
        $router->match($this->buildRequest('GET', '/blog/slug'));
    }

    public function testGenerate(): void
    {
        $routes = new RouteCollection();

        $routes->get('blog', '/blog', 'handler');
        $routes->get('blog_show', '/blog/{id}', 'handler', ['id' => '\d+']);

        $router = new Router($routes);

        self::assertEquals('/blog', $router->generate('blog'));
        self::assertEquals('/blog/5', $router->generate('blog_show', ['id' => 5]));
    }

    public function testGenerateMissingAttributes(): void
    {
        $routes = new RouteCollection();

        $routes->get('blog_show', '/blog/{id}', 'handler', ['id' => '\d+']);

        $router = new Router($routes);

        $this->expectException(InvalidArgumentException::class);
        $router->generate('blog_show', ['slug' => 'post']);
    }

    private function buildRequest($method, $uri): ServerRequest
    {
        return (new ServerRequest())
            ->withMethod($method)
            ->withUri(new Uri($uri));
    }
}
