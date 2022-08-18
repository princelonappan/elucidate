<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\CommonService;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CommonServiceTest extends TestCase
{
    public function testGetInstitutions()
    {
        $httpClient= $this->createMock(\Symfony\Contracts\HttpClient\HttpClientInterface::class);
        $commonService = new CommonService($httpClient);
        $results = $commonService->getInstitutions('Test');
        $this->assertNotEmpty($results);
        $this->assertEquals(200, $results['status']);
        $this->assertNotEmpty($results['institutions']);
    }

    public function testCreateInstitutionTicket()
    {
        $httpClient= $this->createMock(\Symfony\Contracts\HttpClient\HttpClientInterface::class);
        $commonService = new CommonService($httpClient);
        $date = date("Y-m-d\TH:i:s.000\Z", strtotime('now'));
        $postData =  array(
            'project' => 'projects/2a9caad1-19c7-4340-949f-30b81a8a043e',
            'title' => 'missing Institution SAMPLE',
            'description' => 'add Institution SAMPLE',
            'createdAt' => $date,
            'updatedAt' => $date
        );
        $results = $commonService->createInstitutionTicket($postData);
        $this->assertNotEmpty($results);
        $this->assertEquals(201, $results['status']);
    }

    public function testSetTicketData()
    {
        $httpClient= $this->createMock(\Symfony\Contracts\HttpClient\HttpClientInterface::class);
        $commonService = new CommonService($httpClient);
        $results = $commonService->setTicketData('Sample');
        $this->assertNotEmpty($results);
        $this->assertStringContainsString('Sample', $results['title']);
    }
}