<?php

namespace App\UI;

use App\Domain\Vehicle;
use App\Domain\FleetRepository;
use App\Domain\Exceptions\UnknownFleet;
use App\App\RegisterVehicle;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RegisterVehicleHandlerSymfony implements MessageHandlerInterface
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