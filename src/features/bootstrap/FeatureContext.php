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
    private $errorException;

    public function __construct()
    {
        $this->fleet = new Fleet();
        $this->otherFleet = new Fleet();
        $this->vehicle = new Vehicle();
        $this->errorException = new DomainException();
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

    }

    /**
     * @Given I have registered this vehicle into my fleet
     * @When I register this vehicle into my fleet
     * @When I try to register this vehicle into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet()
    {   
        try{
            $this->fleet->register($this->vehicle);  
        }catch(DomainException $e)
        {
            $message = $e->getMessage();
        }  
        Assert::assertEquals(
            'This vehicle has already been registered into your fleet',
            $message
        );  
    }


    /**
     * @Then I should be informed this this vehicle has already been registered into my fleet
     */
    public function iShouldBeInformedThisThisVehicleHasAlreadyBeenRegisteredIntoMyFleet()
    {
        try{
            $this->fleet->register($this->vehicle);  
        }catch(DomainException $e)
        {
            $message = $e->getMessage();
        }
        Assert::assertEquals(
            'This vehicle has already been registered into your fleet',
            $message
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
        }catch(Exception $e)
        {
            $message = $e->getMessage();
        }  
        Assert::assertEquals(
            'This vehicle has already been registered into your fleet',
            $message
        ); 
    }
}
