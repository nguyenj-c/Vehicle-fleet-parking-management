<?php 

namespace App\App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\App\RegisterVehicle;
use App\App\RegisterVehicleHandler;
use App\Infra\ArrayFleetRepository;

class RegisterVehicleCommand extends Command
{

    protected static $defaultName = './fleet_register-vehicle';

    public function __construct(private ArrayFleetRepository $fleetRepository){
        $this->fleetRepository = $fleetRepository;

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
        $commandHandler = new RegisterVehicleHandler($this->fleetRepository);
        $command = new RegisterVehicle($input->getArgument('fleetId'),$input->getArgument('platNumber'));
        $commandHandler($command);
        var_dump($this->fleetRepository);
        return Command::SUCCESS;
    }
}