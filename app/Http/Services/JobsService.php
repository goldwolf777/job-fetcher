<?php

namespace App\Http\Services;


use App\Job;
use DateTime;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class JobsService {

    private $client = null;

    public function __construct() {
        $this->client = new Client(['base_uri' => 'https://paikat.te-palvelut.fi/tpt-api/']);
    }

    public function getJobs($page, $search, $orderBy, $orderDirection, $update, $perPage) {
        $jobsInDbCount = Job::count();
        $jobs = null;
        if($jobsInDbCount > 1 && !$update) {
            Log::info("There is already data in DB will use that");
            $jobs = $this->getJobsFromDb($search, $page, $orderBy, $orderDirection, $perPage)->toJson();
        } else {
            try{
                Log::info("No data in DB or asked to update data from API:".$jobsInDbCount);
                $this->getJobsFromAPI();
                $jobs = $this->getJobsFromDb($search, $page, $orderBy, $orderDirection, $perPage)->toJson();
            } catch (ClientException $e) {
                Log::error("I was unable to fetch the data due to:". $e->getResponse());
            }
        }
        return $jobs;
    }

    private function getJobsFromDb($search, $page, $orderBy, $orderDirection, $perPage) {
        return DB::table('jobs')->where("company", "like", "%".$search."%")
            ->orWhere("job_title", "like", "%".$search."%")
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage,'*','pageName', $page);
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
