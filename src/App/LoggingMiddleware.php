<?php 

namespace App\App;

class LoggingMiddleware implements CommandBusMiddleware
{
    public function __construct(private CommandBusMiddleware $commandBus, private Logger $logger)
    {
    }
    
    public function handle($command)
    {
        $commandClass = get_class($command);

        $this->logger->log("Starting $commandClass");
        $startTime = microtime(true);

        $reponse = $this->commandBus->handle($command);

        $endTime = microtime(true);

        $timeElapsed = $endTime - $startTime;
        $this->logger->log("$timeElapsed");
        $this->logger->log("$commandClass finished without errors");
        return $reponse;
    }
}