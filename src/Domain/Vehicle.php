<?php 

namespace App\Domain;

final class Vehicle
{
    private ?Location $location = null;

    public function __construct(private string $plateNumber)
    {
        if(!preg_match('/^[A-Z]{2}\-[0-9]{3}\-[A-Z]{2}$/', $plateNumber)){
            throw new IncorrectValueConstructor('This plate number don\'t respect the laws');
        }
    }

    public function isParkedAt(Location $location) : bool
    {
        if($this->location === null){
            return false;
        }
        return $location->equalsTo($this->location);
    }

    public function parkAt(Location $location) : Location
    {
        $this->guardAgainstDuplicatePark($location);

        return $this->location = $location;
    }

    private function guardAgainstDuplicatePark(Location $location)
    {
        if ($this->isParkedAt($location)) {
            throw InvalidPark::duplicate();
        } 
    }
    
    public function PlateNumber()
    {
        return $this->plateNumber;
    }
}