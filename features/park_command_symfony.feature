Feature: Test symfony command
  In order to show how to describe commands in Behat
  As a Behat developer
  I need to show simple scenario based on http://symfony.com/doc/2.0/components/console.html#testing-commands

  Background:
    Given a fleet create with a command
    And a vehicle in my fleet
    And I have this vehicle into my fleet

  Scenario: Running park a vehicle
        When I park my vehicle at this location "64.54" "100.45" with "./fleet_localize-vehicle" command 
        Then my vehicle should verify this location "64.54" "100.45"
