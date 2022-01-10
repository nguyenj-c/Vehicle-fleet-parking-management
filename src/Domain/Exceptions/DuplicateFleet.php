<?php 

namespace App\Domain\Exceptions;
use Exception;

class DuplicateFleet extends Exception
{    
    public static function duplicate(){
        return new static ('This fleet has already been registered');
    }
} 