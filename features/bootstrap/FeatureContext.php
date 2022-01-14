<?php

use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

use App\Domain\Fleet;
use App\Domain\Location;

use App\Domain\Exceptions\DuplicateVehicle;
use App\Domain\Exceptions\InvalidPark;

use App\Infra\ArrayFleetRepository;
use App\App\RegisterVehicle;
use App\UI\RegisterVehicleHandlerSymfony;
use App\App\ParkVehicle;
use App\UI\ParkVehicleHandlerSymfony;
use App\App\CreateFleet;
use App\UI\CreateFleetHandlerSymfony;
use App\App\Logger;
use App\App\LoggingMiddleware;
use App\App\MiddlewareBus;
use App\App\RegisterBus;
use App\App\ExecutionTimeMiddleware;
use App\UI\CreateFleetCommand;
use App\UI\RegisterVehicleCommand;
use App\UI\ParkVehicleCommand;


use App\UI\ValidatorMiddleware;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;


/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private Fleet $fleet;
    private Fleet $otherFleet;
    private string $plateNumber;
    private Location $location;
    private ?Exception $latestException = null;
    private ?ArrayFleetRepository $fleetRepository;

    private $application;

    private RegisterBus $registerBus;
    private MiddlewareBus $middlewareBus;
    private MessageBus $bus;

    public function __construct(){
        $this->fleetRepository = new ArrayFleetRepository();
        $this->application = new Application();
        $logger = new Logger();
        
        $createHandler = new CreateFleetHandlerSymfony($this->fleetRepository);
        $registerHandler = new RegisterVehicleHandlerSymfony($this->fleetRepository);
        $parkHandler = new ParkVehicleHandlerSymfony($this->fleetRepository);

        $map = ([ 
            CreateFleet::class => $createHandler, 
            RegisterVehicle::class => $registerHandler, 
            ParkVehicle::class => $parkHandler,
        ]);

        $this->bus = new MessageBus([
            new HandleMessageMiddleware(new HandlersLocator([
                CreateFleet::class => [$createHandler], 
                RegisterVehicle::class => [$registerHandler], 
                ParkVehicle::class => [$parkHandler],
            ])),
            new ValidatorMiddleware($logger),
        ]);
        
        $this->registerBus = new RegisterBus($map);
        $executionTimeMiddleware = new ExecutionTimeMiddleware($logger);
        $loggingMiddelware = new LoggingMiddleware($logger);
        $this->middlewareBus = new MiddlewareBus([$loggingMiddelware,$executionTimeMiddleware], $this->registerBus);
        
    }
    
    /**
     * @Given my fleet
     */
    public function myFleet()
    {
        $commandHandler = new CreateFleetHandlerSymfony($this->fleetRepository);
        $command = new CreateFleet('AAAAA1');
        $this->fleet = $commandHandler($command);
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle()
    {
        $this->plateNumber = 'AA-010-ZZ';
    }

    /**
     * @When I register this vehicle into my fleet
     * @When I try to register this vehicle into my fleet
     * @Given I have registered this vehicle into my fleet
     */
    public function ieRegisterThisVehicleIntoMyFleet()
    {
        try {
            $commandHandler = new RegisterVehicleHandlerSymfony($this->fleetRepository);
            $command = new RegisterVehicle($this->fleet->ID(), $this->plateNumber);
            $commandHandler($command);
        } catch (DuplicateVehicle $e) {
            $this->latestException = $e;
        }    
    }

    /**
     * @Then I should be informed this this vehicle has already been registered into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet()
    {
        Assert::assertInstanceOf(DuplicateVehicle::class, $this->latestException);
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet()
    {
        $fleetVerify = $this->fleetRepository->find($this->fleet->ID());
        Assert::assertNull($this->latestException);
        Assert::assertTrue($fleetVerify->has($this->plateNumber));
    }


    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser()
    {
        $commandHandler = new CreateFleetHandlerSymfony($this->fleetRepository);
        $command = new CreateFleet('AAAAA2');
        $this->otherFleet = $commandHandler($command);
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet()
    {
        $commandHandler = new RegisterVehicleHandlerSymfony($this->fleetRepository);
        $command = new RegisterVehicle($this->otherFleet->ID(), $this->plateNumber);
        $commandHandler($command);
    }

    /**
     * @Given a location
     */
    public function aLocation()
    {
        $this->location = new Location(15.54,64.45);
    }

    /**
     * @Given my vehicle has been parked into this location
     * @When I try to park my vehicle at this location
     * @When I park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation()
    {
        try {
            $commandHandler = new ParkVehicleHandlerSymfony($this->fleetRepository);
            $command = new ParkVehicle($this->fleet->ID(), $this->plateNumber, $this->location->latitude(), $this->location->longitude());
            $commandHandler($command);
        } catch (InvalidPark $e) {
            $this->latestException = $e;
        }
    }

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation()
    {
        $fleetVerify = $this->fleetRepository->find($this->fleet->ID());
        $vehicleFind = $fleetVerify->find($this->plateNumber);
        Assert::assertNotNull($vehicleFind);
        Assert::assertTrue($vehicleFind->verify($this->location));
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation()
    {
        Assert::assertInstanceOf(InvalidPark::class, $this->latestException);
    }

    /**
     * @Given a fleet create with a command
     */
    public function aFleetCreateWithACommand()
    {
        $this->application->add(new CreateFleetCommand($this->bus));
        $command = $this->application->find('./fleet_create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command, 'username' => '000001'));
    }

    /**
     * @Given another fleet create with a command
     */
    public function anAnotherFleetCreateWithACommand()
    {
        $this->application->add(new CreateFleetCommand($this->bus));
        $command = $this->application->find('./fleet_create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command, 'username' => '000002'));
    }

    /**
     * @Then I should see my fleet
     */
    public function iShouldSeeMyFleet()
    {
        Assert::assertNotNull($this->fleetRepository->find('000001'));
    }

    /**
     * @Then I should see the second fleet
     */
    public function iShouldSeeTheSecondFleet()
    {
        Assert::assertNotNull($this->fleetRepository->find('000002'));
    }

    /**
     * @When I try to register with :arg1 command
     */
    public function iTryToRegisterWithCommand($arg1)
    {
        $this->application->add(new RegisterVehicleCommand($this->bus));
        $command = $this->application->find('./fleet_register');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $arg1, 'fleetId' => '000001', 'platNumber' => 'AN-010-ZZ'));
    }


    /**
     * @Given I have this vehicle into my fleet
     * @Then I should see my vehicle in my fleet
     */
    public function iShouldSeeMyVehicleInMyFleet()
    {
        $fleetVerify = $this->fleetRepository->find('000001');
        Assert::assertNotNull($fleetVerify);
        Assert::assertNotNull($fleetVerify->find('AN-010-ZZ'));
    }

    /**
     * @When I try to register in another fleet with :arg1 command
     */
    public function iTryToRegisterInAnotherFleetWithCommand($arg1)
    {
        $this->application->add(new RegisterVehicleCommand($this->bus));
        $command = $this->application->find('./fleet_register');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $arg1, 'fleetId' => '000002', 'platNumber' => 'AN-010-ZZ'));
    }

    /**
     * @Then I should see my vehicle in another fleet
     */
    public function iShouldSeeMyVehicleInAnotherFleet()
    {
        $fleetVerify = $this->fleetRepository->find('000002');
        Assert::assertNotNull($fleetVerify);
        Assert::assertNotNull($fleetVerify->find('AN-010-ZZ'));
    }


    /**
     * @Given a vehicle in my fleet
     */
    public function aVehicleInMyFleet()
    {
        $this->application->add(new RegisterVehicleCommand($this->bus));
        $command = $this->application->find('./fleet_register');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command, 'fleetId' => '000001', 'platNumber' => 'AN-010-ZZ'));
    }

    /**
     * @When I park my vehicle at this location :arg1 :arg2 with :arg3 command
     */
    public function iParkMyVehicleAtThisLocationWithCommand($arg1, $arg2, $arg3)
    {
        $this->application->add(new ParkVehicleCommand($this->bus));
        $command = $this->application->find('./fleet_localize-vehicle');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $arg3, 'fleetId' => '000001', 'platNumber' => 'AN-010-ZZ', 
                                        'latitude' => $arg1,'longitude' => $arg2));
    }

    /**
     * @Then my vehicle should verify this location :arg1 :arg2
     */
    public function myVehicleShouldVerifyThisLocation($arg1, $arg2)
    {
        $location = new Location($arg1, $arg2);
        $fleetVerify = $this->fleetRepository->find('000001');
        $vehicleFind = $fleetVerify->find('AN-010-ZZ');
        Assert::assertNotNull($vehicleFind);
        Assert::assertTrue($vehicleFind->verify($location));
    }
}
