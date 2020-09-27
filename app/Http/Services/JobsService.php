<?php

namespace App\Http\Services;


use App\Job;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class JobsService {

    private $client = null;

    public function __construct() {
        $this->client = new Client(['base_uri' => 'https://paikat.te-palvelut.fi/tpt-api/']);
    }

    public function getJobs() {
        $jobsResponse = $this->getJobsFromAPI();
        return $jobsResponse;
    }

    private function getJobsFromAPI() {
        try {
            $response = $this->client->request('GET','tyopaikat?englanti=true');
            $jsonResponse = json_decode($response->getBody());
            $dataArray =  $jsonResponse->{"response"}->{"docs"};
            $this->saveRetrievedJobsToDb($dataArray);
            return $jsonResponse;
        } catch (ClientException $e) {
            Log::error("I was unable to fetch the data due to:". $e->getResponse());
            return null;
        }
    }

    private function saveRetrievedJobsToDb($dataArray) {
        $dataToSave = array();
        foreach ($dataArray as $value) {
            try {
                $mySqlDateTime = (new DateTime($value->{"ilmoituspaivamaara"}))->format("Y-m-d H:i:s");
                array_push($dataToSave,
                    array('job_title'=>$value->{"otsikko"},
                        'company'=>$value->{"tyonantajanNimi"},
                        'description'=>$value->{"kuvausteksti"},
                        'job_created_at'=>$mySqlDateTime
                        ));
            } catch (\Exception $e) {
                Log::error("I was unable to format the date or get needed parameters, will not save this data:".$value);
            }

        }
        Job::insert($dataToSave);
    }
}
