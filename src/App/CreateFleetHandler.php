<?php

namespace App\App;

use App\Domain\DuplicateFleet;
use App\Domain\Fleet;
use App\Infra\ArrayFleetRepository;

class CreateFleetHandler
{    
    private ArrayFleetRepository $fleetRepository;

    public function __construct(ArrayFleetRepository $fleetRepository){
            $this->fleetRepository = $fleetRepository;
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