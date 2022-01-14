<?php 

namespace App\UI;
use App\App\Logger;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use App\App\CommandBusMiddleware;
use App\App\CreateFleet;
use App\App\ParkVehicle;
use App\App\RegisterVehicle;

class ValidatorMiddleware implements MiddlewareInterface
{
    public function __construct(private Logger $logger)
    {
        
    }
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->logger->log("System validator Start");

        $response = $stack->next()->handle($envelope, $stack);

        $responseClass = $response->getMessage();

        $commandValidate = match (TRUE) {
            ($responseClass instanceof CreateFleet) => CreateFleet::class,
            ($responseClass instanceof RegisterVehicle) => RegisterVehicle::class,
            ($responseClass instanceof ParkVehicle) => ParkVehicle::class,
        };
        $this->logger->log("This is a validate class $commandValidate of our system");
        
        $this->logger->log("System validator Finished");

        return $response;
    }
}