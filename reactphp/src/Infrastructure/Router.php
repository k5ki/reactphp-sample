<?php

declare(strict_types=1);

namespace App\Infrastructure;

use FastRoute;
use FastRoute\Dispatcher\GroupCountBased;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private GroupCountBased $dispatcher;

    public function __construct(FastRoute\RouteCollector $routes)
    {
        $this->dispatcher = new GroupCountBased($routes->getData());
    }

    public function __invoke(ServerRequestInterface $request): Response
    {
        $routeInfo = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                return new Response(Response::STATUS_NOT_FOUND, ['Content-Type' => 'text/plain'], "");
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return new Response(Response::STATUS_METHOD_NOT_ALLOWED, ['Content-Type' => 'text/plain'], "");
            case FastRoute\Dispatcher::FOUND:
                $params = $routeInfo[2];
                return $routeInfo[1]($request, ...array_values($params));
        }
    }
}
