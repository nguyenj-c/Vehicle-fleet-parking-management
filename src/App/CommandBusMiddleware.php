<?php 

namespace App\App;

interface CommandBusMiddleware
{
    public function handle($command);
}