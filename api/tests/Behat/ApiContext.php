<?php

namespace App\Tests\Behat;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\KernelInterface;
use Behat\Behat\Tester\Exception\PendingException;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class ApiContext implements Context
{
    const URL = 'https://localhost:8443';

    /** @var KernelInterface */
    private $kernel;

    private $client;

    private $body;

    private $response;

    private $headers = [
        'accept' => 'application/json'
    ];

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->client = new Client([
            'base_uri' => self::URL,
            'verify' => false,
        ]);
        
    }

    /**
     * @Given the request body is:
     */
    public function theRequestBodyIs(string $body)
    {
        $this->body = $body;
    }

    /**
     * @When I POST to :uri
     */
    public function iPostTo($uri)
    {
        $this->response = $this->client->post($uri,[
            RequestOptions::JSON => json_decode($this->body, true),
        ],
        [
            'headers' => $this->headers
        ]);
    }

    /**
     * @Then the response code is :arg1
     */
    public function theResponseCodeIs($expectedCode)
    {
        return $this->response->getStatusCode() == $expectedCode;
    }

    /**
     * @Given I set the page as :page
     */
    public function iSetThePageAs($page)
    {
        $this->body = 'page=' . $page;
    }

    /**
     * @When I Get the url :uri
     */
    public function iGetTheUrl($uri)
    {
        $this->response = $this->client->get($uri . (!empty($this->body) ? '?' . $this->body: ''), [
            'headers'=> $this->headers,
        ]);
    }

    /**
     * @Then I count at least one Item
     */
    public function iCountAtLeastOneItem()
    {
        $books = json_decode($this->response->getBody()->getContents(), true);
        return count($books) >= 1;
    }

    /**
     * @Given I am an identified user
     */
    public function iAmAnIdentifiedUser()
    {
        $this->headers['Authorization'] = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1OTA0NzE5ODQsImV4cCI6MTU5MDQ3NTU4NCwicm9sZXMiOlsiUk9MRV9VU0VSIl0sInVzZXJuYW1lIjoiaXNjLmpjamxAZ21haWwuY29tIn0.b_5h1tx13o1BfyAWWfyFN9MmV5YSHAjw7Hk1Vuj3T_DNH-unk_UpqLexrhqCg72RRJ2gUiL1N15nF8Inqj7C8VE2W2w7q8JtMN4FrcpXEqMxCJrU00uFnVxCR-j07GwGFWOTnK3-TdPdYyZX36H_rti4Kuwd5FCCdbPSF0HeY7FuGYLJG-It8F2A6jOMetpqI9ZJSeZhtyrL8NzOm-rSdf7czmmHMySDGP4rAmXfGB9E05s-O5FxCqCvpUh81ECx90c4XrBAsPBww3vhVXLJx0bjBiuxFVCCiy_oZAY3OPF73v4jkDRnEp2hkeFyphPAWZTKIOtrMYdtzuQ230BUyBSTOlmjpDCcABCqD7UGnO9y1awX05m6M5c2HwNbb5zdWkMIYsJC8XuECoMGhPLoVRL5C7ymD37wqZuJM8U2CrB6gRHNO2YBoW84F6ogXXzq7L_o0HeBR3n-g9QdxEdDLHdbJbjklOnM8FTJj9vVx-mPKVR93PJZr25Q2mKEYFOkL4IUTxj0bMIxB8r9tZyeOVeZYkbmQiciZQG1b93sSL4b6_2Rk5uEJrga0YJu1oKqMG4Yirg3mV1rt9f8pVWLWlDpkm9oh20B8jZQYYvY3w11mfUNIsoo7u2qFOtaDhhpwQvu87OTiKqy9lm8kas6l5LCvkhBzN6rK6WzteL7Q_8';
    }
}