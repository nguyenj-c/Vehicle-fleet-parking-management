<?php 

namespace App\App;
use App\Domain\Exceptions\InvalidCommand;

class RegisterBus implements CommandBusMiddleware
{
    public function __construct(private array $arrayCommand)
    {
    }
    
    public function handle($command,$next)
    {
        $commandHandler = $this->arrayCommand[$command::class] 
            ?? throw InvalidCommand::unknown();  

        $response = $commandHandler($command);
        return $response;
    }

}