<?php

namespace App\App;
use App\Domain\Location;

class ParkVehicle
{    
    function __construct(private string $fleetID, private string $plateNumber, private float $latitude, private float $longitude) {
    }
    
    public function getFleetID(): string
    {
        return $this->fleetID;
    }

    public function getPlateNumber(): string
    {
        return $this->plateNumber;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }
   
    public function getLongitude(): float
    {
        return $this->longitude;
    }
} 