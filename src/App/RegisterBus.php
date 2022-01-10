<?php 

namespace App\App;
use App\Domain\Exceptions\InvalidCommand;
use App\Infra\ArrayFleetRepository;

class RegisterBus implements CommandBus
{
    public function __construct(private ArrayFleetRepository $fleetRepository, private array $arrayCommand)
    {
        $this->fleetRepository = $fleetRepository;
        $this->arrayCommand = $arrayCommand;
    }
    
    public function handle($command)
    {
        $commandHandler = $this->arrayCommand[$command::class] ?? null;
        if($commandHandler === null){
            throw InvalidCommand::unknown();
        }
        $commandHandler($command);
    }
}