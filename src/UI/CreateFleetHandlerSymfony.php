<?php

namespace App\UI;

use App\Domain\Fleet;
use App\Domain\FleetRepository;
use App\Domain\Exceptions\DuplicateFleet;
use App\App\CreateFleet;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateFleetHandlerSymfony implements MessageHandlerInterface
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
        usleep(500000);
        return $fleet;
    }
    
} 