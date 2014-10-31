Feature: create account
  In order to use the network, an account is required

  Scenario: create account
    Given there's no account in the database with name "Ma27"
    When I send an account creation request with following credentials:
      | username | password | email            |
      | Ma27     | 123456   | ma27@example.org |
    Then I should get get an success message
    And the account should be stored in database

  Scenario Outline: create account with illegal credentials
    Given there's an account with name "Ma27" and email "ma27@example.org"
    When I send an account creation request with following credentials:
      | username | password   | email  |
      | <name>   | <password> | <mail> |
    Then I should get message "<error>" for property "<property>"
  Examples:
    | name | password | mail | error | property |
    |      |          |      |       |          |
