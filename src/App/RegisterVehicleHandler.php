<?php

namespace App\App;

use App\Domain\Vehicle;
use App\Domain\FleetRepository;
use App\Domain\Exceptions\UnknownFleet;
class RegisterVehicleHandler
{    
    private FleetRepository $fleetRepository;

    public function __construct(FleetRepository $fleetRepository){
        $this->fleetRepository = $fleetRepository;
    }

    public function __invoke(RegisterVehicle $registerVehicle) {
        $existFleet = $this->fleetRepository->find($registerVehicle->getFleetID());
        
        if(null === $existFleet){
            throw UnknownFleet::unknown();
        }

        $vehicle = new Vehicle($registerVehicle->getPlateNumber());
        $existFleet->register($vehicle);

        $this->fleetRepository->save($existFleet);
    }
    
} 