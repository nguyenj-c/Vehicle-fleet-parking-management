<?php 

namespace App\UI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use App\App\CreateFleet;
use App\App\CreateFleetHandler;
use App\App\RegisterBus;

class CreateFleetCommand extends Command
{

    protected static $defaultName = './fleet_create';

    public function __construct(RegisterBus $commandBus){
        $this->commandBus = $commandBus;

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
        $command = new CreateFleet($input->getArgument('username'));
        $this->commandBus->handle($command);

        return Command::SUCCESS;
    }
}