<?php

final class Fleet
{
    private $vehicles = [];

    public function register(Vehicle $vehicle) : void
    {
        if(!in_array($vehicle,$this->vehicles)){
            throw new DomainException('This vehicle has already been registered into your fleet');
        }
        array_push($this->vehicules,$vehicle);
    }
}