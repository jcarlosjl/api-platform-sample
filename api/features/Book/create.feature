Feature:
    In order to prove the creation of a new book
    As an API Client

Scenario: I want to Create a book
    Given I am an identified user
        And the request body is:
    """"
    {
        "isbn": "0-19-852663-6",
        "title": "Behat for experts",
        "description": "You can get the advance knowladge about the excited world of behat",
        "author": "Anonymous",
        "publicationDate": "2020-05-29"
    }
    """"
    When I POST to "/books"
    Then The response code is 201