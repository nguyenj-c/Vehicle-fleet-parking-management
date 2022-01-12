#!/usr/bin/env php
<?php
// application.php
require __DIR__ . '/../vendor/autoload.php';

use App\App\CreateFleet;
use App\App\RegisterVehicle;
use App\App\ParkVehicle;

use App\App\RegisterBus;
use App\App\Logger;
use App\App\CreateFleetHandler;
use App\App\LoggingMiddleware;
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
$loggingMiddleware = new LoggingMiddleware($registerBus, $logger);

$application->add(new CreateFleetCommand($loggingMiddleware));
$application->add(new RegisterVehicleCommand($loggingMiddleware));
$application->add(new ParkVehicleCommand($loggingMiddleware));
$application->run();