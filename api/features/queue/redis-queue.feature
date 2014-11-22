@redisQueue
Feature: redis queue
  Implementation of a simple queue based on redis

  Scenario: push data to the queue
    Given I have an entity to push into the queue
     When I have pushed the entity
     Then the queue should contain the entity

  Scenario: pull data from the queue
    Given there are dummy entities stored in the queue
     When I pop them all
     Then the queue should contain no entities
