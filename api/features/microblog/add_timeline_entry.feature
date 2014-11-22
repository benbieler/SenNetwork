@addTimelineEntry
Feature: add timeline entry
  In order to share entries with its followers, it should be able to write timeline posts

  Background:
    Given there are following users:
      | username | email             |
      | Ma27     | Ma27@example.org  |
      | admin    | admin@example.org |
      And I'm logged in as "Ma27"

  Scenario: add entry
    When I add an entry with following input:
      | content           | image     |
      | entry #foo @admin | valid.png |
    Then the entry should be stored in the database
     And the following tags should be recognized:
      | name |
      | foo  |
     And the following users should be marked:
      | name  |
      | admin |

  Scenario Outline: trying to add entry with invalid input
    When I add an entry with following input:
      | content   | image   |
      | <content> | <image> |
    Then the entry should not be stored in the database
     And I should see "<error>"
  Examples:
    | content                                                                                                                | image        | error                                        |
    |                                                                                                                        | valid.png    | Post content too short!                      |
    | any long post, any long post, any long post, any long post, any long post, any long post, any long post, any long post | valid.png    | Post content too long!                       |
    | sample post                                                                                                            | invalid.file | The largest possible size of an image is 1MB |
