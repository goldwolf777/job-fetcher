<?php


namespace App\Http\Services;


use GuzzleHttp\Client;

class JobsFetcherImpl implements JobsFetcher {

    private $client;

    public function __construct() {
        $this->client = new Client(['base_uri' => 'https://paikat.te-palvelut.fi/tpt-api/']);
    }
    public function getJobs() {
        $response = $this->client->request('GET','tyopaikat?englanti=true');
        $jsonResponse = json_decode($response->getBody());
        return $jsonResponse->{"response"}->{"docs"};
    }
}
