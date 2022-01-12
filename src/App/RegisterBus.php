<?php 

namespace App\App;
use App\Domain\Exceptions\InvalidCommand;

class RegisterBus implements CommandBusMiddleware
{
    public function __construct(private array $arrayCommand)
    {
    }
    
    public function handle($command)
    {
        $commandHandler = $this->arrayCommand[$command::class] 
            ?? throw InvalidCommand::unknown();    
        $commandHandler($command);
    }

}