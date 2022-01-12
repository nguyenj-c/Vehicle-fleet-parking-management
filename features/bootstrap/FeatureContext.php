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
use App\App\RegisterVehicleHandler;
use App\App\ParkVehicle;
use App\App\ParkVehicleHandler;
use App\App\CreateFleet;
use App\App\CreateFleetHandler;
use App\App\Logger;
use App\App\LoggingMiddleware;
use App\App\MiddlewareBus;
use App\App\RegisterBus;
use App\App\ResponseTimeMiddleware;
use App\UI\CreateFleetCommand;
use App\UI\RegisterVehicleCommand;
use App\UI\ParkVehicleCommand;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;

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
    private array $map;
    private RegisterBus $registerBus;
    private Logger $logger;
    private LoggingMiddleware $loggingMiddelware;
    private ResponseTimeMiddleware $responseMiddleware;
    private MiddlewareBus $middlewareBus;

    public function __construct(){
        $this->fleetRepository = new ArrayFleetRepository();
        $this->application = new Application();
        $this->logger = new Logger();
        $this->map = ([ 
            CreateFleet::class => new CreateFleetHandler($this->fleetRepository), 
            RegisterVehicle::class => new RegisterVehicleHandler($this->fleetRepository), 
            ParkVehicle::class => new ParkVehicleHandler($this->fleetRepository),
        ]);
        
        $this->registerBus = new RegisterBus($this->map);
        $this->responseMiddleware = new ResponseTimeMiddleware($this->logger, $this->registerBus);
        $this->loggingMiddelware = new LoggingMiddleware($this->logger,$this->responseMiddleware);
        $this->middlewareBus = new MiddlewareBus([$this->loggingMiddelware, $this->responseMiddleware]);
    }
    
    /**
     * @Given my fleet
     */
    public function myFleet()
    {
        $commandHandler = new CreateFleetHandler($this->fleetRepository);
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
            $commandHandler = new RegisterVehicleHandler($this->fleetRepository);
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
        $commandHandler = new CreateFleetHandler($this->fleetRepository);
        $command = new CreateFleet('AAAAA2');
        $this->otherFleet = $commandHandler($command);
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet()
    {
        $commandHandler = new RegisterVehicleHandler($this->fleetRepository);
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
            $commandHandler = new ParkVehicleHandler($this->fleetRepository);
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
        $this->application->add(new CreateFleetCommand($this->middlewareBus));
        $command = $this->application->find('./fleet_create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command, 'username' => '000001'));
    }

    /**
     * @Given another fleet create with a command
     */
    public function anAnotherFleetCreateWithACommand()
    {
        $this->application->add(new CreateFleetCommand($this->middlewareBus));
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
        $this->application->add(new RegisterVehicleCommand($this->middlewareBus));
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
        $this->application->add(new RegisterVehicleCommand($this->middlewareBus));
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
        $this->application->add(new RegisterVehicleCommand($this->middlewareBus));
        $command = $this->application->find('./fleet_register');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command, 'fleetId' => '000001', 'platNumber' => 'AN-010-ZZ'));
    }

    /**
     * @When I park my vehicle at this location :arg1 :arg2 with :arg3 command
     */
    public function iParkMyVehicleAtThisLocationWithCommand($arg1, $arg2, $arg3)
    {
        $this->application->add(new ParkVehicleCommand($this->middlewareBus));
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
