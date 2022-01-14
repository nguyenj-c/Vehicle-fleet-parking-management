<?php 

namespace App\App;


class MiddlewareBus implements CommandBusMiddleware
{
    public function __construct(private array $arrayMiddleware)
    {
    }
    
    public function handle($command,$next)
    {
        foreach (array_reverse($this->arrayMiddleware) as $middleware) {
            $next = function ($command) use ($middleware, $next) {
                return $middleware->handle($command,$next);
            };
        }
        return $next($command);
    }
    
    

}