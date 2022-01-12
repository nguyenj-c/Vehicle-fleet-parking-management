<?php 

namespace App\App;
use App\Domain\Exceptions\InvalidCommand;

class LoggingMiddleware implements CommandBusMiddleware
{
    public function __construct(private CommandBusMiddleware $commandBus, Logger $logger)
    {
        $this->commandBus = $commandBus;
        $this->logger = $logger;
    }
    
    public function handle($command)
    {
        $commandClass = get_class($command);

        $this->logger->log("Starting $commandClass");
        $startTime = microtime(true);

        $reponse = $this->commandBus->handle($command);

        $commandHandler = $this->arrayCommand[$command::class] ?? null;
        if($commandHandler === null){
            throw InvalidCommand::unknown();
        }
        $commandHandler($command);

        $endTime = microtime(true);

        $timeElapsed = $endTime - $startTime;
        $this->logger->log("$timeElapsed");
        $this->logger->log("$commandClass finished without errors");
var_dump($startTime);
        return $reponse;

    }

}