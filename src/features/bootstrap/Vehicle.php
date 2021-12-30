<?php
final class Vehicle
{
    private Location $location;
    private string $numPlaque;

    public function __construct($numPlaque)
    {
        $this->numPlaque = $numPlaque;
    }

    /*
    public function verifyLocation()
    {

    }

    public function parkVehicleAtLocation()
    {

    }*/
}