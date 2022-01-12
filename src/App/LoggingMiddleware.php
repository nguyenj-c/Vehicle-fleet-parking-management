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

        $this->logger->log("$commandClass finished without errors");
    }
}