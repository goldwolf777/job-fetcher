<?php
namespace App\Http\Controllers;

use App\Http\Services\JobsService;
use Illuminate\Http\Response;

class JobsController extends Controller {

    private $jobsService = null;

    public function __construct() {
        $this->jobsService = new JobsService();
    }

    public function getJobs() {
        $response = $this->jobsService->getJobs();
        return response($response, Response::HTTP_OK);
    }

}
