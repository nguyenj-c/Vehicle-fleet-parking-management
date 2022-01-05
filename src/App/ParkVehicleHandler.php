<?php

namespace App\App;
use App\Domain\Vehicle;
use App\Domain\Location;
use App\Infra\FleetRepository;

class ParkVehicleHandler
{    
    private FleetRepository $fleetRepository;

    public function __construct(FleetRepository $fleetRepository){
            $this->fleetRepository = $fleetRepository;
    }

    public function __invoke(ParkVehicle $parkVehicle) {
        $fleet = $this->fleetRepository->find($parkVehicle->getFleetID());

        
        $vehicle = new Vehicle($parkVehicle->getPlateNumber());
        $location = new Location($parkVehicle->getLatitude(), $parkVehicle->getLongitude());
        $fleet->park($vehicle, $location);

        $this->fleetRepository->save($fleet);
    }
    
} 