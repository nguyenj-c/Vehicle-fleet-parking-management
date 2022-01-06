#!/usr/bin/env php
<?php
// application.php
require __DIR__ . '/../vendor/autoload.php';

use App\App\RegisterBus;
use App\App\RegisterVehicleBus;
use App\UI\CreateFleetCommand;
use App\UI\RegisterVehicleCommand;
use App\Infra\ArrayFleetRepository;
use Symfony\Component\Console\Application;

$application = new Application();
$fleetRepository= new ArrayFleetRepository();
// ... register commands

$registerBus = new RegisterBus($fleetRepository);


$application->add(new CreateFleetCommand($registerBus));
$application->add(new RegisterVehicleCommand($registerBus));
$application->run();