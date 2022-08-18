<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InstitutionControllerTest extends WebTestCase
{
    private $client;
    private $baseUrl;

    public function setUp(): void
    {
        $this->baseUrl = getenv('TEST_BASE_URL');
        $this->client = static::createClient();
    }

    public function testAPIRequestValidation(): void
    {
        $this->client->request('GET', $this->baseUrl . '/api/institutions');
        $message = $this->client->getResponse()->getContent();
        $this->assertStringContainsString('This value should not be blank', $message);
    }

    public function testAPIWithInvalidToken(): void
    {
        $_SERVER['TOKEN'] = 'Test Token';
        $this->client->request('GET', $this->baseUrl . '/api/institutions?search=USA');
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertNotEmpty($data);
        $this->assertEquals(401, $data['status']);
    }

    public function testAPIWithSearchResults(): void
    {
        $_SERVER['TOKEN'] = getenv('TOKEN');
        $this->client->request('GET', $this->baseUrl . '/api/institutions?search=USA');
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertNotEmpty($data);
        $this->assertEquals(200, $data['status']);
        $this->assertNotEmpty($data['institutions']);
    }

    public function testAPIWithTicketCreation(): void
    {
        $_SERVER['TOKEN'] = getenv('TOKEN');
        $this->client->request('GET', $this->baseUrl . '/api/institutions?search=TESTED1234776');
        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertNotEmpty($data);
        $this->assertEquals(201, $data['status']);
        $this->assertStringContainsString('Ticket created', $data['message']);
    }
}