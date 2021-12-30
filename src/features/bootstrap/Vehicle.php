<?php
final class Vehicle
{
    private $location;
    private $numPlaque;

    public function __construct($numPlaque)
    {
        $this->numPlaque = $numPlaque;
    }
    
    public function getNumPlaque() : string
    {
        return $this->numPlaque;
    }
    /*
    public function verifyLocation()
    {

    }

    public function parkVehicleAtLocation()
    {

    }*/
}