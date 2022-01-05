<?php

namespace App\App;

use App\Domain\DuplicateFleet;
use App\Domain\Fleet;
use App\Infra\FleetRepository;

use function PHPUnit\Framework\throwException;

class CreateFleetHandler
{    
    private FleetRepository $fleetRepository;

    public function __construct(FleetRepository $fleetRepository){
            $this->fleetRepository = $fleetRepository;
    }

    public function __invoke(CreateFleet $createFleet) {
        $existFleet = $this->fleetRepository->has($createFleet->getIdFleet());
        
        if($existFleet){
            throw DuplicateFleet::duplicate();
        }

        $fleet = new Fleet($createFleet->getIdFleet());
        
        $this->fleetRepository->save($fleet);
        
        return $fleet;
    }
    
} 