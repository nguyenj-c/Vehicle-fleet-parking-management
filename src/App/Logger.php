<?php 

namespace App\App;

class Logger
{
    public function log($info) : void
    {
        echo "LOG : $info\n";
    }
}