<?php


namespace App\Http\Repository;

interface JobsRepository {

    public function getJobsFromDb($search, $page, $orderBy, $orderDirection, $perPage);

    public function upsertJobsArray($dataToSave);

    public function countJobs();


}
