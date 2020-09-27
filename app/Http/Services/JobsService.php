<?php

namespace App\Http\Services;


use App\Job;
use DateTime;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class JobsService {

    private $client = null;

    public function __construct() {
        $this->client = new Client(['base_uri' => 'https://paikat.te-palvelut.fi/tpt-api/']);
    }

    public function getJobs($page, $search, $orderBy, $orderDirection) {
        $jobsInDbCount = Job::count();
        $jobs = null;
        if($jobsInDbCount > 1) {
            $jobs = json_encode($this->getJobsFromDb($search, $page));
        } else {
            try{
                $this->getJobsFromAPI();
                $jobs = json_encode($this->getJobsFromDb($search, $page));
            } catch (ClientException $e) {
                Log::error("I was unable to fetch the data due to:". $e->getResponse());
            }
        }
        return $jobs;
    }

    private function getJobsFromDb($search, $page) {
        return DB::table('jobs')->where("company", "like", "%".$search."%")
            ->orWhere("description", "like", "%".$search."%")
            ->orWhere("job_title", "like", "%".$search."%")
            ->paginate(50,'*','pageName', $page);
    }

    private function getJobsFromAPI() {
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
