<?php 

namespace App\UI;

use App\App\RegisterBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\App\RegisterVehicle;
use App\App\RegisterVehicleBus;
use App\App\RegisterVehicleHandler;


class RegisterVehicleCommand extends Command
{

    protected static $defaultName = './fleet_register-vehicle';

    public function __construct(RegisterBus $commandBus){
        $this->commandBus = $commandBus;

        parent::__construct();
    }
    protected function configure(): void
    {
        $this->addArgument('fleetId', InputArgument::REQUIRED, 'The plate number of the vehicle.');
        $this->addArgument('platNumber', InputArgument::REQUIRED, 'The plate number of the vehicle.');
    }
    
    // ...
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Vehicle Creator',
            '============',
            '',
        ]);
    
        // retrieve the argument value using getArgument()
        $output->writeln('Fleet of the user: '.$input->getArgument('fleetId'));
        $output->writeln('Vehicle of the user: '.$input->getArgument('platNumber'));
        $command = new RegisterVehicle($input->getArgument('fleetId'),$input->getArgument('platNumber'));
        $this->commandBus->handle($command);

        return Command::SUCCESS;
    }
}