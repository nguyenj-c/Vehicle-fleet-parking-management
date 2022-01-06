<?php 

namespace App\App;
use App\Domain\FleetRepository;

class RegisterBus implements CommandBus
{
    public function __construct(private FleetRepository $fleetRepository)
    {
    }
    
    public function handle($command){
        $commandHandler = new CreateFleetHandler($this->fleetRepository);
        $commandHandler($command);
    }
}