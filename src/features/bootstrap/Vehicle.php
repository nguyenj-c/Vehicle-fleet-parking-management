<?php
final class Vehicle
{
    private Location $location;
    private string $numPlaque;

    public function __construct(string $numPlaque)
    {
        $this->numPlaque = $numPlaque;
    }

    
    public function verifyLocation(Location $location) : bool
    {
        if($this->location == $location){
            return true;
        }
        return false;
    }

    public function parkVehicleAtLocation(Location $location)
    {
        $this->location = $location;
    }
}