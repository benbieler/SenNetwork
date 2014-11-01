@requestToken
Feature: request api token
  In order to access the api, an api token is required, which should be
  requestable on the server

  Scenario: login with invalid credentials
    When I send a token request with the following credentials:
      | username | password |
      | foo      | 123456   |
    Then I should see "Invalid credentials"

  Scenario: login with valid credentials
    When I send a token request with the following credentials:
      | username | password |
      | Ma27     | foobar   |
    Then I should have an api token
    And the token should be stored in the database

  Scenario: login with locked credentials
    When I send a token request with the following credentials:
      | username | password |
      | locked   | 123456   |
    Then I should see "This account is locked"
