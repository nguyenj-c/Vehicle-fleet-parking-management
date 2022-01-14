<?php 

namespace App\App;

class ResponseTimeMiddleware implements CommandBusMiddleware
{
    public function __construct(private Logger $logger)
    {
    }
    
    public function handle($command,$next)
    {
        $startTime = microtime(true);

        $response = $next($command,$next);
        
        $endTime = microtime(true);

        $timeElapsed = $endTime - $startTime;
        $this->logger->log("$timeElapsed");
        return $response;
    }
}