<?php

use Framework\Http\ResponseSender;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\ServerRequestFactory;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
function print_pre($var, $flag = 0)
{
    echo '<pre>' . print_r($var, 1) . '</pre>';
    if ($flag !== 0) {exit();}
}

$request = ServerRequestFactory::fromGlobals();

$name = $request->getQueryParams()['name'] ?? 'Guest';

$response = (new HtmlResponse('Hello, ' . $name . '!'))
    ->withHeader('X-Developer', 'AlexSaM');

$emitter = new ResponseSender();
$emitter->send($response);
