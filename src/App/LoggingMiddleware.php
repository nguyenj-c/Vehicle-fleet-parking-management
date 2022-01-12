<?php 

namespace App\App;

class LoggingMiddleware implements CommandBusMiddleware
{
    public function __construct(private Logger $logger, private ?CommandBusMiddleware $next)
    {
    }
    
    public function handle($command)
    {
        $commandClass = get_class($command);


        $this->logger->log("Starting $commandClass");
        if($this->next === null){
            return;
        }
        $response = $this->next->handle($command);
        $this->logger->log("$commandClass finished without errors");
        return $response;
    }
}