<?php 

namespace App\App;

class RegisterVehicle
{    
    function __construct(private string $fleetId, private string $plateNumber)
    {
    }

    public function getFleetID(): string
    {
        return $this->fleetId;
    }

    public function getPlateNumber(): string
    {
        return $this->plateNumber;
    }
} 