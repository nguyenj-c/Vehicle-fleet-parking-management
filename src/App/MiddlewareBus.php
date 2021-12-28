<?php 

namespace App\App;


class MiddlewareBus implements CommandBusMiddleware
{
    public function __construct(private array $arrayMiddleware, private CommandBusMiddleware $commandBusMiddleware)
    {
    }
    
    public function handle(object $command, $next)
    {

        foreach (array_reverse($this->arrayMiddleware) as $middleware) {
            $next ??= $this->commandBusMiddleware->handle($command,null);
            $next = function ($command) use ($middleware, $next) {
                return $middleware->handle($command,$next);
            };
        }
        return $next($command);
    }
     
}