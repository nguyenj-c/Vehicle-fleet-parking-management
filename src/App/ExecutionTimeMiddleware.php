<?php 

namespace App\App;

class ExecutionTimeMiddleware implements CommandBusMiddleware
{
    public function __construct(private Logger $logger)
    {
    }
    
    public function handle(object $command,?callable $next)
    {
        $startTime = microtime(true);

        $response = $next($command);

        $endTime = microtime(true);

        $timeElapsedInSeconds = $endTime - $startTime;
        $timeElapsedInMilliSeconds = $timeElapsedInSeconds * pow(10,3);
        $this->logger->log("Exectution time: $timeElapsedInMilliSeconds ms.");

        return $response;
    }
}