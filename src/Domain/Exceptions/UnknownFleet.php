<?php 

namespace App\Domain\Exceptions;
use Exception;

class UnknownFleet extends Exception
{    
    public static function unknown(){
        return new static ('This fleet doesn\'t exist');
    }
} 