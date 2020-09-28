<?php


namespace App\Http\Services;


use DateTime;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JobFetcherImpl implements JobFetcher {

    private $client;

    public function __construct() {
        $this->client = new Client(['base_uri' => 'https://paikat.te-palvelut.fi/tpt-api/']);
    }
    public function getJobs() {
        $response = $this->client->request('GET','tyopaikat?englanti=true');
        $jsonResponse = json_decode($response->getBody());
        $dataArray =  $jsonResponse->{"response"}->{"docs"};
        $this->saveRetrievedJobsToDb($dataArray);
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
                        'external_id'=>$value->{"id"},
                        'job_created_at'=>$mySqlDateTime
                    ));
            } catch (\Exception $e) {
                Log::error("I was unable to format the date or get needed parameters, will not save this data:".$value);
            }
        }
        DB::table('jobs')->upsert($dataToSave, ['external_id'], ['job_title','company','description','job_created_at','updated_at']);
    }
}
