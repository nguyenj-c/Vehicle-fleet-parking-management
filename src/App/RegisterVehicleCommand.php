<?php 

namespace App\App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\App\RegisterVehicle;
use App\App\RegisterVehicleHandler;
use App\Infra\FleetRepository;

class RegisterVehicleCommand extends Command
{

    protected static $defaultName = './fleet register-vehicle';

    public function __construct(private FleetRepository $fleetRepository){
        $this->fleetRepository = $fleetRepository;

        parent::__construct();
    }
    protected function configure(): void
    {
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
        $output->writeln('Vehicle of the user: '.$input->getArgument('platNumber'));
        $commandHandler = new RegisterVehicleHandler($this->fleetRepository);
        $command = new RegisterVehicle($input->getArgument('platNumber'));
        $commandHandler($command);
        return Command::SUCCESS;
    }
}