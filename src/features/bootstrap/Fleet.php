<?php

final class Fleet
{
    private $vehicles;
    private $vehicle;
    private $idFleet;

    public function __construct($idFleet)
    {
        $this->idFleet = $idFleet;
        $this->vehicles = [];
    }

    public function register(Vehicle $vehicle) : void
    {
        if(in_array($vehicle,$this->vehicles) == true){
            throw new DomainException('This vehicle has already been registered into your fleet');
        }
        $this->vehicle = $vehicle;

        array_push($this->vehicles,$this->vehicle);
    }

    public function hasVehicle(Vehicle $vehicle) : bool
    { 
        if(in_array($vehicle,$this->vehicles) == true){
            return true;
        }
        return false;
    }

}