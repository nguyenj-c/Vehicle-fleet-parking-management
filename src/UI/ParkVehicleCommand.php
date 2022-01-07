<?php 

namespace App\UI;

use App\App\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\App\RegisterVehicle;

class ParkVehicleCommand extends Command
{

    protected static $defaultName = './fleet_localize-vehicle';

    public function __construct(CommandBus $commandBus){
        $this->commandBus = $commandBus;

        parent::__construct();
    }
    protected function configure(): void
    {
        $this->addArgument('fleetId', InputArgument::REQUIRED, 'The plate number of the vehicle.');
        $this->addArgument('platNumber', InputArgument::REQUIRED, 'The plate number of the vehicle.');        
        $this->addArgument('latitude', InputArgument::REQUIRED, 'The latitude of the location.');        
        $this->addArgument('longitude', InputArgument::REQUIRED, 'The longitude of the location.');
    }
    
    // ...
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Vehicle park',
            '============',
            '',
        ]);
    
        // retrieve the argument value using getArgument()
        $output->writeln('Fleet of the user: '.$input->getArgument('fleetId'));
        $output->writeln('Vehicle of the user: '.$input->getArgument('platNumber'));
        $output->writeln('The latitude of the location: '.$input->getArgument('latitude'));
        $output->writeln('The longitude of the location: '.$input->getArgument('longitude'));

        $command = new RegisterVehicle($input->getArgument('fleetId'),$input->getArgument('platNumber'),$input->getArgument('latitude'),$input->getArgument('longitude'));
        $this->commandBus->handle($command);

        return Command::SUCCESS;
    }
}