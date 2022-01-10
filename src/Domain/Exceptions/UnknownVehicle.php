<?php 

namespace App\Domain\Exceptions;
use Exception;
class UnknownVehicle extends Exception
{
    public static function unknown(){
        return new static ('This vehicle isn\'t in your fleet');
    }
}