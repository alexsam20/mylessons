<?php
declare(strict_types=1);

use Framework\Http\ActionResolver;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\SimpleRouter;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * @param mixed $var
 * @param int|bool|string $flag
 * @return void
 */
function print_pre(mixed $var, int|bool|string $flag = 0): void
{
    echo '<pre>' . print_r($var, true) . '</pre>';
    if ($flag !== 0) { exit(); }
}
### Initialization

$aura = new \Aura\Router\RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', App\Http\Action\HelloAction::class);
$routes->get('about', '/about', App\Http\Action\AboutAction::class);
$routes->get('blog', '/blog', App\Http\Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', App\Http\Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);

$router = new \Framework\Http\Router\AuraRouterAdapter($aura);
$resolver = new ActionResolver();

### Running

$request = ServerRequestFactory::fromGlobals();
try{
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $action = $resolver->resolve($result->getHandler());
    $response = $action($request);
} catch (RequestNotMatchedException $e) {
    $response = new JsonResponse(['error' => 'Undefined page'], 404);
}

### Postprocessing

$response = $response->withHeader('X-Developer', 'AlexSaM');

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
