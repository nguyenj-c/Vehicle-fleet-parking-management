#!/usr/bin/env php
<?php
// application.php
require __DIR__ . '/../vendor/autoload.php';

use App\App\CreateFleet;
use App\App\RegisterVehicle;
use App\App\ParkVehicle;

use App\App\RegisterBus;
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
$registerBus = new RegisterBus($fleetRepository,$map);

$application->add(new CreateFleetCommand($registerBus));
$application->add(new RegisterVehicleCommand($registerBus));
$application->add(new ParkVehicleCommand($registerBus));
$application->run();