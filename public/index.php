<?php
declare(strict_types=1);

use App\Http\Action;
use App\Http\Middleware;
use Aura\Router\RouterContainer;
use Framework\Http\Application;
use Framework\Http\Pipeline\MiddlewareResolver;
use Framework\Http\Router\AuraRouterAdapter;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use Psr\Http\Message\ServerRequestInterface;

ini_set('display_errors', 'Off');
chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Debug function
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

$params = [
    'debug' => false,
    'users' => ['admin' => 'password', 'alex' => '12345678']
];

$aura = new RouterContainer();
$routes = $aura->getMap();

$routes->get('home', '/', Action\HelloAction::class);
$routes->get('about', '/about', Action\AboutAction::class);

$routes->get('cabinet', '/cabinet', [
    new Middleware\BasicAuthMiddleware($params['users']),
    Action\CabinetAction::class,
]);

$routes->get('blog', '/blog', Action\Blog\IndexAction::class);
$routes->get('blog_show', '/blog/{id}', Action\Blog\ShowAction::class)->tokens(['id' => '\d+']);

$router = new AuraRouterAdapter($aura);

$resolver = new MiddlewareResolver();
$app = new Application($resolver, new Middleware\NotFoundHandler());

$app->pipe(new Middleware\ErrorHandlerMiddleware($params['debug']));
$app->pipe(Middleware\CredentialsMiddleware::class);
$app->pipe(Middleware\ProfilerMiddleware::class);
### Running

$request = ServerRequestFactory::fromGlobals();
try{
    $result = $router->match($request);
    foreach ($result->getAttributes() as $attribute => $value) {
        $request = $request->withAttribute($attribute, $value);
    }
    $app->pipe($result->getHandler());
} catch (RequestNotMatchedException $e) {}

$response = $app->run($request);

### Sending

$emitter = new SapiEmitter();
$emitter->emit($response);
