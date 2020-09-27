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
        $page = $request->input("page") != null ? $request->input("page") :  1;
        $search = $request->input("search") != null ? $request->input("search") : "";
        $orderBy = $request->input("orderBy") != null ? $request->input("orderBy") : "job_created_at";
        $orderDirection = $request->input("orderDirection") ? $request->input("orderDirection") : "DESC";
        $update = $request->input("update") != null ?  $request->input("update") : false;
        $response = $this->jobsService->getJobs($page, $search, $orderBy, $orderDirection, $update);
        return response($response, Response::HTTP_OK);
    }
}
