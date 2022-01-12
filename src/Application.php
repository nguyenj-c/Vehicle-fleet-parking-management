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
use App\App\ResponseTimeMiddleware;

use App\App\CreateFleetHandler;
use App\App\RegisterVehicleHandler;
use App\App\ParkVehicleHandler;
use App\UI\CreateFleetCommand;
use App\UI\RegisterVehicleCommand;
use App\UI\ParkVehicleCommand;
use App\Infra\ArrayFleetRepository;

use Symfony\Component\Console\Application;

$application = new Application();
$fleetRepository= new ArrayFleetRepository();
// ... register commands

$map = ([ 
    CreateFleet::class => new CreateFleetHandler($fleetRepository), 
    RegisterVehicle::class => new RegisterVehicleHandler($fleetRepository), 
    ParkVehicle::class => new ParkVehicleHandler($fleetRepository),
]);
$logger = new Logger();

$registerBus = new RegisterBus($map, $logger);

$responseMiddleware = new ResponseTimeMiddleware($logger, $registerBus);
$loggingMiddleware = new LoggingMiddleware($logger, $responseMiddleware);

$middlewareBus = new MiddlewareBus([$loggingMiddleware,$responseMiddleware]);

$application->add(new CreateFleetCommand($middlewareBus));
$application->add(new RegisterVehicleCommand($middlewareBus));
$application->add(new ParkVehicleCommand($middlewareBus));
$application->run();