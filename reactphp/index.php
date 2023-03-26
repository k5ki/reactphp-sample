<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use App\Infrastructure\Router;
use React\Http\Message\Response;

$routes = new FastRoute\RouteCollector(new FastRoute\RouteParser\Std(), new FastRoute\DataGenerator\GroupCountBased());
$routes->get('/', function (Psr\Http\Message\ServerRequestInterface $request) {
    return new Response(
        Response::STATUS_OK,
        ['Content-Type' => 'text/plain'],
        "Hello World!\n"
    );
});

$http = new React\Http\HttpServer(new Router($routes));
$socket = new React\Socket\SocketServer('0.0.0.0:8080');
$http->listen($socket);

echo "Listening on " . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;
