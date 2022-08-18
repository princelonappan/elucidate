<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CommonService
{
    private $client;
    private $errorMessage = array('status' => 503, 'message' => 'Please try after sometime.');

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getInstitutions($search): array
    {
        $url = $_SERVER['INSTITUTION_URL'].'?fullSearch='. $search;
        $curlOptions = array(
            CURLOPT_HTTPHEADER => array('Content-type: application/ld+json',
                'Authorization: Bearer ' . $_SERVER['TOKEN']
            )
        );
        $curlResponse = $this->curlRequest($url, $curlOptions);
        if ($curlResponse['error']) {
            return $this->errorMessage;
        }
        $response = json_decode($curlResponse['response'], true);
        $info = $curlResponse['info'];
        if (empty($response) || empty($info)) {
            return $this->errorMessage;
        }

        if ($info['http_code'] == 200) {
            $data = array('status' => 200, 'count' => $response['hydra:totalItems'], 'institutions' => $response['hydra:member']);
        } else {
            $data = array('status' => $info['http_code'], 'count' => 0, 'message' => $response['message']);
        }

        return $data;
    }

    public function createInstitutionTicket($postData): array
    {
        $url = $_SERVER['CREATE_TICKET_URL'];
        $curlOptions = array(
            CURLOPT_HTTPHEADER => array('Content-type: application/ld+json',
                'Authorization: Bearer ' . $_SERVER['TOKEN']
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        );
        $curlResponse = $this->curlRequest($url, $curlOptions);
        if ($curlResponse['error']) {
            return $this->errorMessage;;
        }
        $response = json_decode($curlResponse['response'], true);
        $info = $curlResponse['info'];
        if (empty($response) || empty($info)) {
            return $this->errorMessage;
        }

        if ($info['http_code'] == 201) {
            $data = array('status' => 201, 'institution_id' => $response['id'], 'message' => 'Ticket created');
        } else {
            $data = array('status' => $info['http_code'], 'message' => $response['message']);
        }

        return $data;
    }

    public function setTicketData($institutionName): array
    {
        $date = date("Y-m-d\TH:i:s.000\Z", strtotime('now'));
        return array(
            'project' => 'projects/2a9caad1-19c7-4340-949f-30b81a8a043e',
            'title' => 'missing Institution ' . $institutionName,
            'description' => 'add Institution ' . $institutionName,
            'createdAt' => $date,
            'updatedAt' => $date
        );
    }

    public function curlRequest($url, $options): array
    {
        $ch = curl_init($url);
        foreach ($options as $key => $val) {
            curl_setopt($ch, $key, $val);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_errno($ch);
        curl_close($ch);
        return array('response' => $response, 'info' => $info, 'error' => $error);
    }
}
