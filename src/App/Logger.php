<?php 

namespace App\App;

use Symfony\Component\Messenger\Stamp\StampInterface;

class Logger
{
    public function log($info) : void
    {
        echo "LOG : $info\n";
    }
}