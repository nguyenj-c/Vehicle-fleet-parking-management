<?php

namespace App\App;

use App\Domain\Vehicle;
use App\Infra\ArrayFleetRepository;
use App\Domain\UnknownFleet;
class RegisterVehicleHandler
{    
    private ArrayFleetRepository $fleetRepository;

    public function __construct(ArrayFleetRepository $fleetRepository){
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