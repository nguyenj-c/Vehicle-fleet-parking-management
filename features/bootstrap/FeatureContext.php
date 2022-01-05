<?php

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

use App\Domain\Fleet;
use App\Domain\Location;
use App\Domain\DuplicateVehicle;
use App\Domain\InvalidPark;
use App\Infra\FleetRepository;
use App\App\RegisterVehicle;
use App\App\RegisterVehicleHandler;
use App\App\ParkVehicle;
use App\App\ParkVehicleHandler;
use App\App\CreateFleet;
use App\App\CreateFleetHandler;

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
    private ?FleetRepository $fleetRepository;

    public function __construct(){
        $this->fleetRepository = new FleetRepository();
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
}
