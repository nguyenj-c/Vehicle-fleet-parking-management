<?php 

namespace App\App;
use App\Domain\Exceptions\InvalidCommand;

class RegisterBus implements CommandBusMiddleware
{
    public function __construct(private array $arrayCommand)
    {
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