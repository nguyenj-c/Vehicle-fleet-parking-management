<?php 

namespace App\Domain;

final class Vehicle
{
    public function __construct(private string $plateNumber, private ?Location $location = null)
    {
        if(!preg_match('/^[A-Z]{2}\-[0-9]{3}\-[A-Z]{2}$/', $plateNumber)){
            throw new IncorrectValueConstructor('This plate number don\'t respect the laws');
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

    
    public function plateNumber()
    {
        return $this->plateNumber;
    }
}