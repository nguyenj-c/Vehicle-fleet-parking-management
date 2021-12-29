<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */

    private $fleet;
    private $otherFleet;
    private $location;
    private $vehicle;

    public function __construct()
    {
        $this->fleet = new Fleet();
        $this->otherFleet = new Fleet();
        $this->vehicle = new Vehicle();
    }
    

    /**
     * @Given my fleet
     */
    public function myFleet()
    {
        $this->fleet;
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle()
    {
        $this->vehicle;
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet()
    {
        $this->fleet->hasThisVehicule();
    }

    /**
     * @Given I have registered this vehicle into my fleet
     * @When I register this vehicle into my fleet
     * @When I try to register this vehicle into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet()
    {      
        Assert::assertSame(
            'This vehicle has been registered into your fleet',
            $this->vehicle->registerInFleet()
        );  
    }


    /**
     * @Then I should be informed this this vehicle has already been registered into my fleet
     */
    public function iShouldBeInformedThisThisVehicleHasAlreadyBeenRegisteredIntoMyFleet()
    {
        Assert::assertSame(
            'This vehicle has already been registered into your fleet',
            $this->vehicle->vehicleAlreadyRegistered()   
        );   
    }

    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser()
    {
        $this->otherFleet;   
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet()
    {
        Assert::assertSame(
            'This vehicle has been registered into your fleet',
            $this->vehicle->registerInFleet()
        );  
    }
}
