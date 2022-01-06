<?php 

namespace App\Domain;
use Exception;

class InvalidCommand extends Exception
{    
    public static function unknown(){
        return new static ('This command doesn\'t exist');
    }

}    