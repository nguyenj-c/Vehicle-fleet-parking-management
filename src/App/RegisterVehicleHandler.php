<?php

namespace App\App;

use App\Domain\UnknownVehicle;
use App\Domain\Vehicle;
use App\Infra\FleetRepository;

use function PHPUnit\Framework\throwException;

class RegisterVehicleHandler
{    
    private FleetRepository $fleetRepository;

    public function __construct(FleetRepository $fleetRepository){
            $this->fleetRepository = $fleetRepository;
    }

    public function __invoke(RegisterVehicle $registerVehicle) {
        $fleet = $this->fleetRepository->find($registerVehicle->getFleetID());

        $vehicle = new Vehicle($registerVehicle->getPlateNumber());
        $fleet->register($vehicle);

        $this->fleetRepository->save($fleet);
    }
    
} 