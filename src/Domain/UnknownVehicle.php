<?php 

namespace App\Domain;
use Exception;
class UnknownVehicle extends Exception
{
    function __construct() {
    }
    
    public static function unknown(){
        return new static ('This vehicle isn\'t in your fleet');
    }
}