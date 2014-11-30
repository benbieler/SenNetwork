@createAccount
Feature: create account
  In order to use the network, an account is required

  Scenario: create account
    Given there's no account in the database with name "Ma27"
     When I send an account creation request with following credentials:
      | username | password | email            | realname |
      | Ma27     | 123456   | ma27@example.org | Ma27     |
     Then I should get get an success message
      And the account should be stored in database

  Scenario Outline: create account with illegal credentials
    Given there's an account with name "Ma27_2" and email "ma27-2@example.org"
     When I send an account creation request with following credentials:
       | username   | password   | email  | realname   |
       | <username> | <password> | <mail> | <realName> |
     Then I should get message "<error>" for property "<property>"
  Examples:
    | username                          | password | mail               | realName | error                                                                      | property |
    |                                   | 123456   | Ma27@example.org   | Ma27     | Username must contain a minimum of three characters                        | username |
    | as                                | 123456   | Ma27@example.org   | Ma27     | Username must contain a minimum of three characters                        | username |
    | \´"                               | 123456   | Ma27@example.org   | Ma27     | Username can contain alphanumeric characters and the chars _, . and - only | username |
    | Ma27Ma27Ma27Ma27Ma27Ma27Ma27Ma27M | 123456   | Ma27@example.org   | Ma27     | Username can contain a maximum of 32 characters only                       | username |
    | Ma27                              | 12345    | Ma27@example.org   | Ma27     | Password should have at least six characters                               | password |
    | Ma27                              | 123456   |                    | Ma27     | Email cannot be empty                                                      | email    |
    | Ma27                              | 123456   | invalid_mail       | Ma27     | Email contains invalid characters                                          | email    |
    | Ma27                              | 123456   | Ma27@example.org   | Ma       | Your name should have at least three characters                            | realName |
    | Ma27                              | 123456   | Ma27@example.org   | &%$      | Real name contains invalid characters                                      | realName |
    | Ma27_2                            | 123456   | Ma27@example.org   | Ma27_2   | Username Ma27_2 already in use                                             | username |
    | Ma27                              | 123456   | ma27-2@example.org | Ma27     | Email ma27-2@example.org already in use                                    | email    |
