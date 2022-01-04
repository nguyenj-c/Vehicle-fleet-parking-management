<?php 

namespace App\Domain;
use Exception;

class InvalidPark extends Exception
{    
    function __construct() {
    }

    public static function duplicate(){
        return new static ('My vehicle is already parked here');
    }

}    