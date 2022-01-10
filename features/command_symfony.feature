Feature: Test symfony command
  In order to show how to describe commands in Behat
  As a Behat developer
  I need to show simple scenario based on http://symfony.com/doc/2.0/components/console.html#testing-commands

  Scenario: Running Create fleet command
    When I run "./fleet_create" command
    Then I should see my fleet
  Scenario: Running Register vehicle command
    When I try to run "./fleet_register" command
    Then I should see my vehicle in my fleet
    """ 
    Fleet of the user: 000001
    Vehicle of the user: AA-010-ZZ
    """
    