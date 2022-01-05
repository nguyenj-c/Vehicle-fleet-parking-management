#!/usr/bin/env php
<?php
// application.php
require __DIR__ . '/../vendor/autoload.php';

use App\App\CreateFleetCommand;
use App\Infra\FleetRepository;
use Symfony\Component\Console\Application;

$application = new Application();

// ... register commands
$fleetRepository = new FleetRepository();

$application->add(new CreateFleetCommand($fleetRepository));
$application->run();