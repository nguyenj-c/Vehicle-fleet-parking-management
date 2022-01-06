<?php 

namespace App\Domain;
use Exception;

class IncorrectValueConstructor extends Exception
{
    public static function location(){
        return new static ('The value for latitude and longitude don\'t respect the standards');
    }

    public static function vehicle(){
        return new static ('This plate number don\'t respect the laws');
    }

    public static function fleet(){
        return new static ('This ID don\'t respect the standards');
    }
}