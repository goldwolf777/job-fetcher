<?php

namespace App\Http\Services;


use App\Job;
use DB;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Log;

class JobsService {

    protected $jobFetcher;

    public function __construct(JobFetcher $fetcher) {
        $this->jobFetcher = $fetcher;
    }

    public function getJobs($page, $search, $orderBy, $orderDirection, $update, $perPage) {
        $jobsInDbCount = Job::count();
        $jobs = null;
        if($jobsInDbCount > 1 && !$update) {
            Log::info("There is already data in DB will use that");
            $jobs = $this->getJobsFromDb($search, $page, $orderBy, $orderDirection, $perPage)->toJson();
        } else {
            try{
                Log::info("No data in DB or asked to update data from API");
                $this->jobFetcher->getJobs();
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
}
