<?php 

namespace App\App;
use App\Domain\FleetRepository;
use App\Domain\InvalidCommand;

class RegisterBus implements CommandBus
{
    public function __construct(private FleetRepository $fleetRepository)
    {
    }
    
    public function handle($command)
    {
        if($command instanceof CreateFleet){
            $commandHandler = new CreateFleetHandler($this->fleetRepository);
            $commandHandler($command);
        }

        if($command instanceof RegisterVehicle){
            $commandHandler = new RegisterVehicleHandler($this->fleetRepository);
            $commandHandler($command);
        }

        if($command instanceof ParkVehicle){
            $commandHandler = new ParkVehicleHandler($this->fleetRepository);
            $commandHandler($command);
        }
        
        if(!($command instanceof CreateFleet) &&  !($command instanceof RegisterVehicle) && !($command instanceof ParkVehicle)){
            throw InvalidCommand::unknown();
        }
    }
}