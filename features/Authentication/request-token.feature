@requestApiKey
Feature: request api key
  In order to use the sententiaregum api, an api key is required

  Background:
    Given these users are registered
     | name       | password | email             | lock  |
     | Maximilian | s3cr3t   | me@example.org    | false |
     | Guest      | 123456   | guest@example.org | true  |

  Scenario: successful authentication
    Given my name is "Maximilian"
      And I don't have an api key
     When I login with the following credentials:
       | username   | password |
       | Maximilian | s3cr3t   |
     Then I should have an api key

  Scenario Outline: invalid credentials
    Given my name is "Maximilian"
      And I don't have an api key
     When I login with the following credentials:
       | username   | password    |
       | <username> | <password>  |
     Then I should see "<message>"
  Examples:
    | username   | password | message                 |
    | Maximilian | invalid  | Invalid credentials     |
    | invalid    | s3cr3t   | Invalid credentials     |
    | Guest      | 123456   | This account is locked! |
