@follower-advice
Feature: follower advices
  In order to find new users to follow the application should provide advice lists

  Background:
    Given there are following users:
        | username  | email                 |
        | Ma27      | ma27@example.org      |
        | admin     | admin@example.org     |
        | foo       | foo@example.org       |
        | bar       | bar@example.org       |
        | baz       | baz@example.org       |
        | foobar    | foobar@example.org    |
        | foobarbaz | foobarbaz@example.org |
      And I'm logged in as "Ma27"
      And there are the following follower relations:
        | user  | following |
        | admin | foo       |
        | admin | bar       |

  Scenario: get random advice
    When I search for advices
    Then the advices should be in the following list:
      | username  |
      | admin     |
      | foo       |
      | bar       |
      | baz       |
      | foobar    |
      | foobarbaz |

  Scenario: find following by following
    Given I follow the following users:
      | name  |
      | admin |
     When I search for advices
     Then I should get the following advices:
      | name |
      | foo  |
      | bar  |
