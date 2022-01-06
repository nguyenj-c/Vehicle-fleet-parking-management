<?php 

namespace App\App;
use App\Domain\FleetRepository;

class RegisterVehicleBus implements CommandBus
{
    public function __construct(private FleetRepository $fleetRepository)
    {
    }
    
    public function handle($command){
        $commandHandler = new RegisterVehicleHandler($this->fleetRepository);
        $commandHandler($command);
    }
}