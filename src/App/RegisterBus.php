<?php 

namespace App\App;
use App\Domain\Exceptions\InvalidCommand;

class RegisterBus implements CommandBusMiddleware
{
    public function __construct(private array $arrayCommand, Logger $logger)
    {
        $this->arrayCommand = $arrayCommand;
        $this->logger = $logger;
    }
    
    public function handle($command)
    {
        $commandClass = get_class($command);

        $this->logger->log("Starting $commandClass");
        $startTime = microtime(true);

        $commandHandler = $this->arrayCommand[$command::class] ?? null;
        if($commandHandler === null){
            throw InvalidCommand::unknown();
        }
        $commandHandler($command);

        $endTime = microtime(true);

        $timeElapsed = $endTime - $startTime;
        $this->logger->log("$timeElapsed");
        $this->logger->log("$commandClass finished without errors");

    }

}