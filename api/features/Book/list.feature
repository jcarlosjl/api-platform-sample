Feature:
    In order to prove the list of books
    As an API Client
Scenario: I want to get the list of books
    Given I am an identified user
        And I set the page as 1
    When I Get the url "/books"
    Then The response code is 200
    Then I count at least one Item