<?php

use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ServerRequestInterface;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
function print_pre($var, $flag = 0)
{
    echo '<pre>' . print_r($var, true) . '</pre>';
    if ($flag !== 0) { exit(); }
}
### Initialization
$request = ServerRequestFactory::fromGlobals();

### Action
$path = $request->getUri()->getPath();
$action = null;

if ($path === '/') {
    $action = static function (ServerRequestInterface $request) {
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        return new HtmlResponse('Hello, ' . $name . '!');
    };
} elseif ($path === '/about') {
    $action = function () {
        return new HtmlResponse('About Me!');
    };
}  elseif ($path === '/blog') {
    $action = function () {
        return new JsonResponse([
            ['id' => 2, 'title' => 'The Second Post'],
            ['id' => 1, 'title' => 'The First Post'],
        ]);
    };
} elseif (preg_match('#^/blog/(?P<id>\d+)$#i', $path, $matches)) {
    $request = $request->withAttribute('id', $matches['id']);

    $action = function (ServerRequestInterface $request) {
        $id = $request->getAttribute('id');
        if ($id > 2) {
            return new JsonResponse(['error' => 'Too many posts'], 404);
        }
        return new JsonResponse(['id' => $id, 'title' => 'Post #' . $id]);
    };
}

if ($action) {
    $response = $action($request);
} else {
    $response = new JsonResponse(['error' => 'Undefined pade.'], 404);
}

### Postprocessing
$response = $response->withHeader('X-Developer', 'AlexSaM');

### Sending
$emitter = new SapiEmitter();
$emitter->emit($response);
