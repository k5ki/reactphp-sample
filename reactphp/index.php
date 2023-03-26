<?php

require __DIR__ . '/vendor/autoload.php';

use React\Http\Message\Response;

$routes = new FastRoute\RouteCollector(new FastRoute\RouteParser\Std(), new FastRoute\DataGenerator\GroupCountBased());
$routes->get('/', function (Psr\Http\Message\ServerRequestInterface $request) {
    return new Response(
        Response::STATUS_OK,
        ['Content-Type' => 'text/plain'],
        "Hello World!\n"
    );
});


$router = function (Psr\Http\Message\ServerRequestInterface $request) use ($routes) {
    $dispatcher = new FastRoute\Dispatcher\GroupCountBased($routes->getData());
    $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            return new Response(Response::STATUS_NOT_FOUND, ['Content-Type' => 'text/plain'], "");
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            return new Response(Response::STATUS_METHOD_NOT_ALLOWED, ['Content-Type' => 'text/plain'], "");
        case FastRoute\Dispatcher::FOUND:
            $params = $routeInfo[2];
            return $routeInfo[1]($request, ...array_values($params));
    }
};

$http = new React\Http\HttpServer($router);
$socket = new React\Socket\SocketServer('0.0.0.0:8080');
$http->listen($socket);

echo "Listening on " . str_replace('tcp:', 'http:', $socket->getAddress()) . PHP_EOL;
