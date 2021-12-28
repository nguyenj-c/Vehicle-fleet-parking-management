<?php

namespace App\UI;
use App\Domain\Vehicle;
use App\Domain\Location;
use App\Domain\FleetRepository;
use App\Domain\Exceptions\UnknownFleet;
use App\App\ParkVehicle;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ParkVehicleHandlerSymfony implements MessageHandlerInterface
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