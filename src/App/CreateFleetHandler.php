<?php

namespace App\App;

use App\Domain\Fleet;
use App\Domain\FleetRepository;
use App\Domain\Exceptions\DuplicateFleet;

class CreateFleetHandler
{    

    public function __construct(private FleetRepository $fleetRepository){
    }

    public function __invoke(CreateFleet $createFleet) {
        $existFleet = $this->fleetRepository->find($createFleet->getIdFleet());

        if(null !== $existFleet){
            throw DuplicateFleet::duplicate();
        }

        $fleet = new Fleet($createFleet->getIdFleet());
        
        $this->fleetRepository->save($fleet);
        
        return $fleet;
    }
    
} 