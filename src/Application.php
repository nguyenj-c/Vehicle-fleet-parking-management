#!/usr/bin/env php
<?php
// application.php
require __DIR__ . '/../vendor/autoload.php';

use App\UI\CreateFleetCommand;
use App\UI\RegisterVehicleCommand;
use App\Infra\ArrayFleetRepository;
use Symfony\Component\Console\Application;

$application = new Application();
$fleetRepository= new ArrayFleetRepository();
// ... register commands


$application->add(new CreateFleetCommand($fleetRepository));
$application->add(new RegisterVehicleCommand($fleetRepository));
$application->run();