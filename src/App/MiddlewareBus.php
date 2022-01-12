<?php 

namespace App\App;


class MiddlewareBus implements CommandBusMiddleware
{
    public function __construct(private array $arrayMiddleware)
    {
    }
    
    public function handle($command)
    {
        foreach($this->arrayMiddleware as $middleware){
            $middleware->handle($command);
        }   
    }

}