<?php

namespace App\App;

use App\Domain\Vehicle;
use App\Domain\FleetRepository;
use App\Domain\Exceptions\UnknownFleet;
class RegisterVehicleHandler
{    

    public function __construct(private FleetRepository $fleetRepository){
    }

    public function __invoke(RegisterVehicle $registerVehicle) {
        $existFleet = $this->fleetRepository->find($registerVehicle->getFleetID())
            ?? throw UnknownFleet::unknown();

        $vehicle = new Vehicle($registerVehicle->getPlateNumber());
        $existFleet->register($vehicle);

        $this->fleetRepository->save($existFleet);
    }
    
} 