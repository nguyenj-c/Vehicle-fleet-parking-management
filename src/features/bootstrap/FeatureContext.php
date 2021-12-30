<?php

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use Exception;

use function PHPUnit\Framework\assertNull;

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

    /**
     * @Given my fleet
     */
    public function myFleet()
    {
        $this->fleet = new Fleet(1);
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle()
    {
        $this->vehicle = new Vehicle('1');
    }

    /**
     * @When I register this vehicle into my fleet
     * @When I try to register this vehicle into my fleet
     * @Given I have registered this vehicle into my fleet
     */
    public function ieRegisterThisVehicleIntoMyFleet()
    {
        try {
            $this->fleet->register($this->vehicle);
        } catch (Exception $e) {
            $this->latestException = $e;
        }
    }

    /**
     * @Then I should be informed this this vehicle has already been registered into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet()
    {
        Assert::assertInstanceOf(DomainException::class, $this->latestException);
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet()
    {
        Assert:assertNull($this->latestException);
        Assert::assertTrue($this->fleet->has($this->vehicle));
    }


    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser()
    {
        $this->otherFleet = new Fleet(2);
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet()
    {
        $this->otherFleet->register($this->vehicle);
    }

    /**
     * @Given a location
     */
    public function aLocation()
    {
        $this->location = new Location(14,12);
    }

    /**
     * @Given my vehicle has been parked into this location
     * @When I park my vehicle at this location
     * @When I try to park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation()
    {
        $this->location->registerPark($this->vehicle);
        $this->vehicle->parkAt($this->location);
    }

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation()
    {
        Assert::assertTrue($this->vehicle->isParkedAt($this->location));
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation()
    {
        try {
            $this->location->isParked($this->vehicle);
        } catch(DomainException $e) {
            Assert::assertSame(
                'My vehicle is already parked here',
                $e->getMessage()
            );
        } finally {
            Assert::assertEquals(
                null,
                $this->latestException
            );
        }
    }
}
