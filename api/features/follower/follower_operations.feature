@follower
Feature: followers
  In order to get in touch with other users you may follow those

  Background:
    Given there are following users:
      | username | email             |
      | Ma27     | ma27@example.com  |
      | admin    | admin@example.com |
      | foo      | foo@example.com   |

  Scenario: create follower relation
    Given I'm logged in as "Ma27"
      And I don't follow "foo"
      And I don't follow "admin"
     When I create a follower relation with "foo"
     Then I should follow "foo"

  Scenario: list follower relations
    Given I'm logged in as "Ma27"
      And I follow the following users:
        | name  |
        | admin |
        | foo   |
     When I list all followers
     Then I should see the users "admin, foo"

  Scenario: drop follower relation
    Given I'm logged in as "Ma27"
      And I follow the following users:
        | name |
        | foo  |
     When I drop the relation with user "foo"
     Then I should no longer follow "foo"
