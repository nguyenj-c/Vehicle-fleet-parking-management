<?php 

namespace App\App;

class ResponseTimeMiddleware implements CommandBusMiddleware
{
    public function __construct(private Logger $logger, private ?CommandBusMiddleware $next)
    {
    }
    
    public function handle($command)
    {
        $startTime = microtime(true);

        if($this->next === null){
            return;
        }
        $response = $this->next->handle($command);
        $endTime = microtime(true);

        $timeElapsed = $endTime - $startTime;
        $this->logger->log("$timeElapsed");
        return $response;
    }
}