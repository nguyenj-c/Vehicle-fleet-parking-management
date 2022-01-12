<?php 

namespace App\Domain;
use App\Domain\Exceptions\IncorrectValueConstructor;

final class Vehicle
{
    public function __construct(private string $plateNumber, private ?Location $location = null)
    {
        if(!preg_match('/^[A-Z]{2}\-[0-9]{3}\-[A-Z]{2}$/', $plateNumber)){
            throw IncorrectValueConstructor::vehicle();
        }
    }

    public function verify(Location $location) : bool
    {
        if($this->location === null){
            return false;
        }

        return $this->location->equalsTo($location);
    }

    public function parkAt(Location $location) : Vehicle
    {
        return new Vehicle($this->plateNumber(), $location);
    }
    
    public function plateNumber() : string
    {
        return $this->plateNumber;
    }
}