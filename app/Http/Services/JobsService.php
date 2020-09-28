<?php

namespace App\Http\Services;


use App\Http\Repository\JobsRepository;
use DateTime;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class JobsService {

    protected $jobFetcher;
    protected $jobsRepository;

    public function __construct(JobsFetcher $fetcher, JobsRepository $jobsRepository) {
        $this->jobFetcher = $fetcher;
        $this->jobsRepository = $jobsRepository;
    }

    public function getJobs($page, $search, $orderBy, $orderDirection, $update, $perPage) {
        $jobsInDbCount = $this->jobsRepository->countJobs();
        $jobs = null;
        if($jobsInDbCount > 1 && !$update) {
            Log::info("There is already data in DB will use that");
            $jobs = $this->jobsRepository->getJobsFromDb($search, $page, $orderBy, $orderDirection, $perPage);
        } else {
            try{
                Log::info("No data in DB or asked to update data from API");
                $dataToSave = $this->jobFetcher->getJobs();
                if($dataToSave === null) {
                    return null;
                }
                $this->saveRetrievedJobsToDb($dataToSave);
                $jobs = $this->jobsRepository->getJobsFromDb($search, $page, $orderBy, $orderDirection, $perPage);
            } catch (ClientException $e) {
                Log::error("I was unable to fetch the data due to:". $e->getResponse());
            }
        }
        return $jobs;
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
        $this->jobsRepository->upsertJobsArray($dataToSave);
    }
}
