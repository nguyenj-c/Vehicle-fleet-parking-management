#!/usr/bin/env php
<?php
// application.php
require __DIR__ . '/../vendor/autoload.php';

use App\App\CreateFleet;
use App\App\RegisterVehicle;
use App\App\ParkVehicle;

use App\App\RegisterBus;
use App\App\Logger;

use App\App\MiddlewareBus;
use App\App\LoggingMiddleware;
use App\App\ExecutionTimeMiddleware;

use App\App\CreateFleetHandler;
use App\App\RegisterVehicleHandler;
use App\App\ParkVehicleHandler;
use App\UI\CreateFleetCommand;
use App\UI\RegisterVehicleCommand;
use App\UI\ParkVehicleCommand;
use App\Infra\ArrayFleetRepository;


use Symfony\Component\Console\Application;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

$application = new Application();
$fleetRepository= new ArrayFleetRepository();

$createHandler = new CreateFleetHandler($fleetRepository);
$registerHandler = new RegisterVehicleHandler($fleetRepository);
$parkHandler = new ParkVehicleHandler($fleetRepository);

$bus = new MessageBus([
    new HandleMessageMiddleware(new HandlersLocator([
        CreateFleet::class => [$createHandler], 
        RegisterVehicle::class => [$registerHandler], 
        ParkVehicle::class => [$parkHandler],
    ])),
]);
    

// ... register commands

$map = ([ 
    CreateFleet::class => $createHandler, 
    RegisterVehicle::class => $registerHandler, 
    ParkVehicle::class => $parkHandler,
]);
$logger = new Logger();

$registerBus = new RegisterBus($map, $logger);

$responseMiddleware = new ExecutionTimeMiddleware($logger, $registerBus);
$loggingMiddleware = new LoggingMiddleware($logger, $responseMiddleware);

$middlewareBus = new MiddlewareBus([$loggingMiddleware,$responseMiddleware],$registerBus);

$application->add(new CreateFleetCommand($bus));
$application->add(new RegisterVehicleCommand($bus));
$application->add(new ParkVehicleCommand($bus));
$application->run();