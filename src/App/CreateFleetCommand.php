<?php 

namespace App\App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\App\CreateFleet;
use App\App\CreateFleetHandler;
use App\Infra\FleetRepository;

class CreateFleetCommand extends Command
{

    protected static $defaultName = './fleet';

    public function __construct(private FleetRepository $fleetRepository){
        $this->fleetRepository = $fleetRepository;

        parent::__construct();
    }
    protected function configure(): void
    {
        $this->addArgument('username', InputArgument::REQUIRED, 'The username of the user.');
    }
    
    // ...
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln([
            'Fleet Creator',
            '============',
            '',
        ]);
    
        // retrieve the argument value using getArgument()
        $output->writeln('Fleet of the user: '.$input->getArgument('username'));
        $commandHandler = new CreateFleetHandler($this->fleetRepository);
        $command = new CreateFleet($input->getArgument('username'));
        $commandHandler($command);
        var_dump($this->fleetRepository);
        return Command::SUCCESS;
    }
}