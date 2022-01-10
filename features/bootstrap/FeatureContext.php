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

use App\App\RegisterBus;
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
    private string $display;

    public function __construct(){
        $this->fleetRepository = new ArrayFleetRepository();
        $this->application = new Application();
        $this->map = ([ 
            CreateFleet::class => new CreateFleetHandler($this->fleetRepository), 
            RegisterVehicle::class => new RegisterVehicleHandler($this->fleetRepository), 
            ParkVehicle::class => new ParkVehicleHandler($this->fleetRepository),
        ]);
        
        $this->registerBus = new RegisterBus($this->map);
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
        Assert::assertInstanceOf(InvalidPark::class, $this->latestException);
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation()
    {
        Assert::assertInstanceOf(InvalidPark::class, $this->latestException);
    }

    /**
     * @When I run :arg1 command
     */
    public function iRunCommand($arg1)
    {
        $this->application->add(new CreateFleetCommand($this->registerBus));
        $command = $this->application->find('./fleet_create');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $arg1, 'username' => '000001'));
        $this->display = $commandTester->getDisplay();
    }

    /**
     * @Then I should see my fleet
     */
    public function iShouldSeeMyFleet()
    {
        Assert::assertNotNull($this->fleetRepository->find('000001'));
    }

    /**
     * @When I try to run :arg1 command
     */
    public function iTryToRunCommand($arg1)
    {
        $this->application->add(new RegisterVehicleCommand($this->registerBus));
        $command = $this->application->find('./fleet_register');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $arg1, 'fleetId' => '000001', 'platNumber' => 'AN-010-ZZ'));
        $this->display = $commandTester->getDisplay();
    }


    /**
     * @Then I should see my vehicle in my fleet
     */
    public function iShouldSeeMyVehicleInMyFleet(PyStringNode $string)
    {
        $fleetVerify = $this->fleetRepository->find('000001');
        Assert::assertNotNull($fleetVerify);
        Assert::assertNotNull($fleetVerify->find('AN-010-ZZ'));
    }
}
