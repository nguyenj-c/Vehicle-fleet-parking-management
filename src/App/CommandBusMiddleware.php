<?php 

namespace App\App;

interface CommandBusMiddleware
{
    public function handle(object $command,?callable $next);
}