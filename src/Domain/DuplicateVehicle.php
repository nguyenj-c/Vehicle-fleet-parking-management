<?php 

namespace App\Domain;
use Exception;

class DuplicateVehicle extends Exception
{    
    public static function duplicate(){
        return new static ('This vehicle has already been registered into your fleet');
    }
} 