<?php

namespace App\App;
use App\Domain\Vehicle;
use App\Domain\Location;
use App\Domain\FleetRepository;
use App\Domain\Exceptions\UnknownFleet;

class ParkVehicleHandler
{    

    public function __construct(private FleetRepository $fleetRepository){
    }

    public function __invoke(ParkVehicle $parkVehicle) {
        $existFleet = $this->fleetRepository->find($parkVehicle->getFleetID())
            ?? throw UnknownFleet::unknown();

        $vehicle = new Vehicle($parkVehicle->getPlateNumber());
        $location = new Location($parkVehicle->getLatitude(), $parkVehicle->getLongitude());
        $existFleet->park($vehicle, $location);

        $this->fleetRepository->save($existFleet);
    }
    
} 