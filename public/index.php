<?php

use Framework\Http\Request;

chdir(dirname(__DIR__));
require 'vendor/autoload.php';
function print_pre($var, $flag = 0)
{
    echo '<pre>' . print_r($var, 1) . '</pre>';
    if ($flag !== 0) {exit();}
}

$request = new Request();
$request->withQueryParams($_GET);
$request->withParsedBody($_POST);

$name = $request->getQueryParams()['name'] ?? 'Guest';
header('X-Developer: AlexSaM');
echo 'Hello, ' . $name . '!' . PHP_EOL;
