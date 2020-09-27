<?php
namespace App\Http\Controllers;

use Illuminate\Http\Response;

class JobsController extends Controller {
    public function getJobs() {
        return response("Hello There", Response::HTTP_OK);
    }
}
