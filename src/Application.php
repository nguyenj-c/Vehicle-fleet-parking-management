#!/usr/bin/env php
<?php
// application.php
require __DIR__ . '/../vendor/autoload.php';

use App\App\CreateFleetCommand;
use App\App\RegisterVehicleCommand;
use App\Infra\FleetRepository;
use Symfony\Component\Console\Application;

$application = new Application();
$fleetRepository= new FleetRepository();
// ... register commands


$application->add(new CreateFleetCommand($fleetRepository));
$application->add(new RegisterVehicleCommand($fleetRepository));
$application->run();