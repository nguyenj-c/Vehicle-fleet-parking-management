<?php

namespace App\App;
use App\Domain\Vehicle;
use App\Domain\Location;
use App\Infra\ArrayFleetRepository;
use App\Domain\UnknownFleet;

class ParkVehicleHandler
{    
    private ArrayFleetRepository $fleetRepository;

    public function __construct(ArrayFleetRepository $fleetRepository){
            $this->fleetRepository = $fleetRepository;
    }

    public function __invoke(ParkVehicle $parkVehicle) {
        $existFleet = $this->fleetRepository->find($parkVehicle->getFleetID());
        
        if(null === $existFleet){
            throw UnknownFleet::unknown();
        }

        $vehicle = new Vehicle($parkVehicle->getPlateNumber());
        $location = new Location($parkVehicle->getLatitude(), $parkVehicle->getLongitude());
        $existFleet->park($vehicle, $location);

        $this->fleetRepository->save($existFleet);
    }
    
} 