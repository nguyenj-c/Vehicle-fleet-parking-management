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
        if(in_array($vehicle->getNumPlaque(),$this->vehicles) == true){
            throw new DomainException('This vehicle has already been registered into your fleet');
        }
        $this->vehicle = $vehicle->getNumPlaque();

        array_push($this->vehicles,$this->vehicle);
    }

    public function getVehicles() : array
    {
        return $this->vehicles;
    }

}