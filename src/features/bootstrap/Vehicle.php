<?php
final class Vehicle
{
    private $fleet;
    private $location;
    private $numPlaque;

    public function __construct($numPlaque)
    {
        $this->numPlaque = $numPlaque;
    }

    public function getFleet() : Fleet
    {
        return $this->fleet;
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