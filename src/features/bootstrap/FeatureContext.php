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
    private $vehicle;
    private $location;

    public function __construct()
    {
        $this->fleet = new Fleet('1');
        $this->otherFleet = new Fleet('2');
        $this->vehicle = new Vehicle('1');
        $this->location = new Location();
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
     * @When I register this vehicle into my fleet
     */
    public function ieRegisterThisVehicleIntoMyFleet()
    {   
        $this->fleet->register($this->vehicle); 
    }

    /**
     * @Given I have registered this vehicle into my fleet
     * @When I try to register this vehicle into my fleet
     * @Then I should be informed this this vehicle has already been registered into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet()
    {   
        try{
            $this->fleet->register($this->vehicle);  
        }catch(DomainException $e)
        {
            $message = $e->getMessage();
            Assert::assertEquals(
                'This vehicle has already been registered into your fleet',
                $message
            );
        }    
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet()
    {
        // TODO
        Assert::assertContains(
            $this->vehicle->getNumPlaque(),
            $this->fleet->getVehicles()
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
        try{
            $this->otherFleet->register($this->vehicle);  
        }catch(DomainException $e)
        {
            $message = $e->getMessage();
            Assert::assertEquals(
                'This vehicle has already been registered into your fleet',
                $message
            ); 
        }  
    }

    /**
     * @Given a location
     */
    public function aLocation()
    {
        $this->location;
    }

    /**
     * @When I park my vehicle at this location
     * @Given my vehicle has been parked into this location
     * @When I try to park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation()
    {
        $this->vehicle->parkVehicleAtLocation();
    }

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation()
    {
        $this->vehicle->verifyLocation();
    }

    /**
     */
    public function myVehicleHasBeenParkedIntoThisLocation()
    {
        throw new PendingException();
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation()
    {
        throw new PendingException();
    }
}
