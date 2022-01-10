Feature: Test symfony command
  In order to show how to describe commands in Behat
  As a Behat developer
  I need to show simple scenario based on http://symfony.com/doc/2.0/components/console.html#testing-commands

  Background:
    Given a fleet create with a command
    Given an another fleet create with a command

  Scenario: Running Create fleet command
    Then I should see my fleet

  Scenario: Running Register Vehicle command
    When I try to register with "./fleet_register" command
    Then I should see my vehicle in my fleet

  Scenario: Running Register Vehicle command
    When I try to register in another fleet with "./fleet_register" command
    Then I should see my vehicle in another fleet