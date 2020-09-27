<?php
namespace App\Http\Controllers;

use App\Http\Services\JobsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JobsController extends Controller {

    private $jobsService = null;

    public function __construct() {
        $this->jobsService = new JobsService();
    }

    public function getJobs(Request $request) {
        $page = 2;
        $search = "";
        $orderBy = "job_created_at";
        $orderDirection = "DESC";
        if($request->input("page")) {
            $page = $request->input("page");
        }
        $response = $this->jobsService->getJobs($page, $search, $orderBy, $orderDirection);
        return response($response, Response::HTTP_OK);
    }

}
