<?php

namespace Tests\Framework\Http\Pipeline;

use Framework\Http\Pipeline\MiddlewareResolver;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;
//use Zend\Diactoros\ServerRequest;

class MiddlewareResolverTest extends TestCase
{
    /**
     * @dataProvider getValidHandlers
     * @param $handler
     */
    public function testDirect($handler): void
    {
        $resolver = new MiddlewareResolver();
        $middleware = $resolver->resolve($handler);

        /** @var ResponseInterface $response */
        $response = $middleware(
            (new ServerRequest())->withAttribute('attribute', $value = 'value'),
            new Response(),
            new NotFoundMiddleware()
        );

        self::assertEquals([$value], $response->getHeader('X-Header'));
    }

    /**
     * @dataProvider getValidHandlers
     * @param $handler
     */
    public function testNext($handler): void
    {
        $resolver = new MiddlewareResolver();
        $middleware = $resolver->resolve($handler);

        /** @var ResponseInterface $response */
        $response = $middleware(
            (new ServerRequest())->withAttribute('next', true),
            new Response(),
            new NotFoundMiddleware()
        );

        self::assertEquals(404, $response->getStatusCode());
    }

    public function getValidHandlers(): array
    {
        return [
            'Callable Callback' => [function (ServerRequestInterface $request, callable $next) {
                if ($request->getAttribute('next')) {
                    return $next($request);
                }
                return (new HtmlResponse(''))
                    ->withHeader('X-Header', $request->getAttribute('attribute'));
            }],
            'Callable Class' => [CallableMiddleware::class],
            'Callable Object' => [new CallableMiddleware()],
            'DoublePass Callback' => [function (ServerRequestInterface $request, ResponseInterface $response, callable $next) {
                if ($request->getAttribute('next')) {
                    return $next($request);
                }
                return $response
                    ->withHeader('X-Header', $request->getAttribute('attribute'));
            }],
            'DoublePass Class' => [DoublePassMiddleware::class],
            'DoublePass Object' => [new DoublePassMiddleware()],
            'Interop Class' => [InteropMiddleware::class],
            'Interop Object' => [new InteropMiddleware()],
        ];
    }

    public function testArray(): void
    {
        $resolver = new MiddlewareResolver();

        $middleware = $resolver->resolve([
            new DummyMiddleware(),
            new CallableMiddleware(),
        ]);

        /** @var ResponseInterface $response */
        $response = $middleware(
            (new ServerRequest())->withAttribute('attribute', $value = 'value'),
            new Response(),
            new NotFoundMiddleware()
        );

        self::assertEquals(['dummy'], $response->getHeader('X-Dummy'));
        self::assertEquals([$value], $response->getHeader('X-Header'));
    }
}

class CallableMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        if ($request->getAttribute('next')) {
            return $next($request);
        }
        return (new HtmlResponse(''))
            ->withHeader('X-Header', $request->getAttribute('attribute'));
    }
}

class DoublePassMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if ($request->getAttribute('next')) {
            return $next($request);
        }
        return $response
            ->withHeader('X-Header', $request->getAttribute('attribute'));
    }
}

class InteropMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getAttribute('next')) {
            return $handler->handle($request);
        }
        return (new HtmlResponse(''))
            ->withHeader('X-Header', $request->getAttribute('attribute'));
    }
}

class NotFoundMiddleware
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return new EmptyResponse(404);
    }
}

class DummyMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        return $next($request)
            ->withHeader('X-Dummy', 'dummy');
    }
}
