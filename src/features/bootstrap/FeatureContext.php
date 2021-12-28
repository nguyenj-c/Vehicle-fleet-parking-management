<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

require __DIR__ . '/../../vendor/autoload.php';

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
    private $vehicule;
    private $user;
    private $location;

    public function __construct()
    {
        $this->vehicule = new Vehicule();
        $this->location = new Location();
        $this->user = new User();
        $this->fleet = new Fleet($this->vehicule);
    }
    


    /**
     * @Given my fleet
     */
    public function myFleet()
    {
        $this->fleet->getTotalVehicule();
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle()
    {
        $this->vehicule->getVehicule();
    }

    /**
     * @Given I have registered this vehicle into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet($vehicule)
    {
        $this->user->registerVehiculeInFleet($vehicule);
    }

    /**
     * @Given a location
     */
    public function aLocation()
    {
        $this->location->getLocation();
    }

    /**
     * @When I park my vehicle at this location
     */
    public function iParkMyVehicleAtThisLocation($vehicule)
    {
        $this->location->parkVehicule($vehicule);
    }

    /**
     * @Then the known location of my vehicle should verify this location
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation($vehicule)
    {
        PHPUnit_Framework_Assert::assertSame(
            $vehicule->getLocation(),
            $this->location
        );
    }

    /**
     * @Given my vehicle has been parked into this location
     */
    public function myVehicleHasBeenParkedIntoThisLocation($vehicule)
    {
        $this->location->parkVehicule($vehicule);
    }

    /**
     * @When I try to park my vehicle at this location
     */
    public function iTryToParkMyVehicleAtThisLocation($vehicule)
    {
        $this->location->parkVehicule($vehicule);
    }

    /**
     * @Then I should be informed that my vehicle is already parked at this location
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation($vehicule)
    {
        $this->location->vehiculeAlreadyParked($vehicule);
    }

    /**
     * @When I register this vehicle into my fleet
     */
    public function iRegisterThisVehicleIntoMyFleet($vehicule)
    {
        $this->fleet->addVehicule($vehicule);
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet($vehicule)
    {
        PHPUnit_Framework_Assert::assertSame(
            $vehicule,
            $this->fleet->getTotalVehicule()
        );
    }

    /**
     * @When I try to register this vehicle into my fleet
     */
    public function iTryToRegisterThisVehicleIntoMyFleet($vehicule)
    {
        $this->user->registerVehiculeInFleet($vehicule);
    }

    /**
     * @Then I should be informed this this vehicle has already been registered into my fleet
     */
    public function iShouldBeInformedThisThisVehicleHasAlreadyBeenRegisteredIntoMyFleet($vehicule)
    {
        $this->user->vehiculeAlreadyRegistered($vehicule);
    }

    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser($user,$vehicule)
    {
        $user->registerVehiculeInFleet($vehicule);
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet($user,$vehicule)
    {
        $user->registerVehiculeInFleet($vehicule);
    }
}
