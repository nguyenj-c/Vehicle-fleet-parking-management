<?php 

namespace App\UI;
use App\App\Logger;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Stopwatch\Stopwatch;

class TraceableMiddleware implements MiddlewareInterface
{
    public function __construct(private Logger $logger, private Stopwatch $stopwatch)
    {   
    }
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->logger->log("System start stopwatch execution time");

        $startWatch = $this->stopwatch->start('commande execution');

        $response = $stack->next()->handle($envelope, $stack);

        $endWatch = $this->stopwatch->stop('commande execution');
        echo $endWatch->getDuration() . "\n";


        return $response;
    }
}