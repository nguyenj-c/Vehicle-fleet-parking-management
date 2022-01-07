<?php 

namespace App\App;
use App\Domain\FleetRepository;
use App\Domain\InvalidCommand;
use App\Infra\ArrayFleetRepository;

class RegisterBus implements CommandBus
{
    public function __construct(private array $arrayCommand)
    {
        $this->arrayCommand = $arrayCommand;
    }
    
    public function handle($command)
    {
        while ($element = current($this->arrayCommand)) {
            $key = new $element(new ArrayFleetRepository());
            if ($key::class === $command::class) {
                $commandHandler = $this->arrayCommand[$command::class];
                $commandHandler($command);
                break;
            }
            next($this->arrayCommand);
        }

    }
}