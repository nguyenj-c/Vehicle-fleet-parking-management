<?php 

namespace App\App;

class LoggingMiddleware implements CommandBusMiddleware
{
    public function __construct(private Logger $logger)
    {
    }
    
    public function handle(object $command,?callable $next)
    {
        $commandClass = get_class($command);

        $this->logger->log("Starting $commandClass");

        $response = $next($command);
        
        $this->logger->log("$commandClass finished without errors");
        return $response;
    }
}