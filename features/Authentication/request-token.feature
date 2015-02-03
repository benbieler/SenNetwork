@requestApiKey
Feature: request api key
  In order to use the sententiaregum api, an api key is required

  Background:
    Given these users are registered
     | name       | password | email          | lock  |
     | Maximilian | s3cr3t   | me@example.org | false |

  Scenario: successful authentication
    Given my name is "Maximilian"
      And I don't have an api key
     When I login with the following credentials:
       | username   | password |
       | Maximilian | s3cr3t   |
     Then I should have an api key
