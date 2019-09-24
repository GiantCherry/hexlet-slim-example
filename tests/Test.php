<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8080'
        ]);
    }

    public function testForm()
    {
        $response = $this->client->get('/users');
        $body = $response->getBody()->getContents();
        $this->assertContains('form', $body);
    }

    public function testUsers()
    {
        $response = $this->client->get('/users');
        $body = $response->getBody()->getContents();

        $this->assertContains('Wanda', $body);
        $this->assertContains('Abagail', $body);
    }

    public function testUsersWithTerm()
    {
        $response = $this->client->get('/users?term=alex');
        $body = $response->getBody()->getContents();

        $this->assertContains('Alexandra', $body);
        $this->assertContains('Alexie', $body);
        $this->assertNotContains('Albertha', $body);
        $this->assertNotContains('Betsy', $body);
        $this->assertNotContains('Tiana', $body);
    }

    public function testNotFoundTerm()
    {
        $response = $this->client->get('/users?term=bbbbbb');
        $body = $response->getBody()->getContents();

        $this->assertContains('bbbbbb', $body);
    }
}
