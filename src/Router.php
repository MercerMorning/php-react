<?php

namespace App;

use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\LoopInterface;
use React\Http\Message\Response;

class Router
{
    private $routes = [];

    /**
     * @var LoopInterface
     */
    private $loop;
    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
    }


    public function __invoke(ServerRequestInterface $request)
    {
        $path = trim($request->getUri()->getPath());
        foreach ($this->routes as $pattern => $handler) {
            if (preg_match("~$pattern~", $path)) {
                return $handler($request, $this->loop);
            }
        }
        return $this->notFound($path);
    }

    public function load($filename)
    {
        $routes = require $filename;
        foreach ($routes as $path => $handler) {
            $this->add($path, $handler);
        }
    }

    public function add($path, callable $handler)
    {
        $this->routes[$path] = $handler;
    }

    private function notFound($path)
    {
        return new Response(
            404,
            ['Content-Type' => 'text/html; charset=UTF-8'],
            "Нет обработчика запроса для $path"
        );
    }
}
