<?php


namespace App\Http\Repository;


use App\Job;
use Illuminate\Support\Facades\DB;

class JobsRepositoryImpl implements JobsRepository {

    protected $job;

    public function __construct(Job $job) {
        $this->job=$job;
    }

    public function countJobs() {
        return $this->job::count();
    }

    public function getJobsFromDb($search, $page, $orderBy, $orderDirection, $perPage) {
        return DB::table('jobs')->where("company", "like", "%".$search."%")
            ->orWhere("job_title", "like", "%".$search."%")
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage,'*','pageName', $page);
    }


    public function upsertJobsArray($dataToSave) {
        DB::table('jobs')->upsert($dataToSave, ['external_id'], ['job_title','company','description','job_created_at','updated_at']);
    }

}
