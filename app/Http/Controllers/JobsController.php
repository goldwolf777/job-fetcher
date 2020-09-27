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
        $page = trim($request->input("page")) ? $request->input("page") :  1;
        $search = trim($request->input("search")) ? $request->input("search") : "";
        $perPage = trim($request->input("perPage")) ? $request->input("perPage") : 25;
        $orderBy = trim($request->input("orderBy")) ? $request->input("orderBy") : "job_created_at";
        $orderDirection = trim($request->input("orderDirection")) ? $request->input("orderDirection") : "DESC";
        $update = $request->input("update") ?  $request->input("update") : false;
        $response = $this->jobsService->getJobs($page, $search, $orderBy, $orderDirection, $update, $perPage);
        return response($response, Response::HTTP_OK);
    }
}
