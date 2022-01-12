<?php 

namespace App\App;

class ResponseTimeMiddleware implements CommandBusMiddleware
{
    public function __construct(private CommandBusMiddleware $commandBus, private Logger $logger)
    {
    }
    
    public function handle($command)
    {
        $startTime = microtime(true);

        $reponse = $this->commandBus->handle($command);

        $endTime = microtime(true);

        $timeElapsed = $endTime - $startTime;
        $this->logger->log("$timeElapsed");
        return $reponse;
    }
}