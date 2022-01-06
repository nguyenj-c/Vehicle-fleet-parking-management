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
        $commandHandler = match (TRUE) 
        {
            ($command instanceof CreateFleet) => new CreateFleetHandler($this->fleetRepository),
            ($command instanceof RegisterVehicle) => new RegisterVehicleHandler($this->fleetRepository),
            ($command instanceof ParkVehicle) => new ParkVehicleHandler($this->fleetRepository)
        };    
        $commandHandler($command);

        if(!($command instanceof CreateFleet) &&  !($command instanceof RegisterVehicle) && !($command instanceof ParkVehicle)){
            throw InvalidCommand::unknown();
        }
    }
}