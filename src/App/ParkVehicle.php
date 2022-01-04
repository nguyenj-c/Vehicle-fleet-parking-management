<?php

namespace App\App;
use App\Domain\Location;

class ParkVehicle
{    
    function __construct(private string $fleetID, private string $plateNumber, private Location $location) {
    }
    
    public function getFleetID(): string
    {
        return $this->fleetID;
    }

    public function getPlateNumber(): string
    {
        return $this->plateNumber;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }
    
} 