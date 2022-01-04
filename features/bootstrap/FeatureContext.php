<?php

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;

use App\Domain\Fleet;
use App\Domain\Vehicle;
use App\Domain\Location;
use App\Domain\DuplicateVehicle;
use App\Domain\InvalidPark;
use App\Infra\FleetRepository;
use App\App\RegisterVehicle;
use App\App\RegisterVehicleHandler;
use App\App\ParkVehicle;
use App\App\ParkVehicleHandler;
/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private Fleet $fleet;
    private Fleet $otherFleet;
    private Vehicle $vehicle;
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
        $this->fleet = new Fleet('AAAAA1');
        $this->fleetRepository->save($this->fleet);
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle()
    {
        $this->vehicle = new Vehicle('AA-010-ZZ');
    }

    /**
     * @When I register this vehicle into my fleet
     * @When I try to register this vehicle into my fleet
     * @Given I have registered this vehicle into my fleet
     */
    public function ieRegisterThisVehicleIntoMyFleet()
    {
        $commanfHandler = new RegisterVehicleHandler($this->fleetRepository);
        $commanfHandler(new RegisterVehicle($this->fleet->ID(), $this->vehicle->PlateNumber()));
    }

    /**
     * @Then I should be informed this this vehicle has already been registered into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet()
    {
        try {
            $commanfHandler = new RegisterVehicleHandler($this->fleetRepository);
            $commanfHandler(new RegisterVehicle($this->fleet->ID(), $this->vehicle->PlateNumber()));
        } catch (DuplicateVehicle $e) {
            $this->latestException = $e;
        }
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet()
    {
        $fleetVerify = $this->fleetRepository->find($this->fleet->ID());
        Assert::assertNull($this->latestException);
        Assert::assertTrue($fleetVerify->has($this->vehicle));
    }


    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser()
    {
        $this->otherFleet = new Fleet('AAAAA2');
        $this->fleetRepository->save($this->otherFleet);
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet()
    {
        $commanfHandler = new RegisterVehicleHandler($this->fleetRepository);
        $commanfHandler(new RegisterVehicle($this->otherFleet->ID(), $this->vehicle->PlateNumber()));
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
     * @When I park my vehicle at this location
     * @When I try to park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation()
    {
        try {
            $commanfHandler = new ParkVehicleHandler($this->fleetRepository);
            $commanfHandler(new ParkVehicle($this->fleet->ID(), $this->vehicle->PlateNumber(), $this->location));
        } catch (InvalidPark $e) {
            $this->latestException = $e;
        }
    }

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation()
    {
        Assert::assertTrue($this->fleet->isParkedAt($this->vehicle, $this->location));
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation()
    {
        
        Assert::assertInstanceOf(InvalidPark::class, $this->latestException);
    }
}
