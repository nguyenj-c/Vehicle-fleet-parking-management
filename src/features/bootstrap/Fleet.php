<?php

final class Fleet
{
    private $vehicles = [];

    public function register(Vehicle $vehicle) : void
    {
        if(!in_array($vehicle,$this->vehicles)){
            throw new ErrorException('This vehicle has already been registered into your fleet');
        }
        array_push($this->vehicules,$vehicle);
    }
    public function getVehicles()
    {
        return $this->vehicles;
    }
}