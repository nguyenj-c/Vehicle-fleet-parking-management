<?php 

namespace App\App;

interface CommandBus
{
    public function handle($command);
}