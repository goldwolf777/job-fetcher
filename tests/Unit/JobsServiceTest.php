<?php

namespace Tests\Unit;

use App\Http\Repository\JobsRepository;
use App\Http\Services\JobsFetcher;
use App\Http\Services\JobsService;
use Illuminate\Foundation\Testing\TestCase;
use Tests\CreatesApplication;

class JobsServiceTest extends TestCase {

    protected $returnObject;

    public function setUp(): void{
        parent::setUp();
       $this->returnObject = (object) [array('job_title'=>"Lead Developer",
            'company'=>"Jobilla",
            'description'=>"Just The Best Job You Could Have",
            'external_id'=>"debugger deesnt work ;( "
        )];
    }

    use CreatesApplication;


    public function testGetJobsWithUpdateFlagTrue() {
        $fetcherMock = $this->createMock(JobsFetcher::class);
        $repositoryMock = $this->createMock(JobsRepository::class);
        $jobFetcher = new JobsService($fetcherMock, $repositoryMock);
        $response = $jobFetcher -> getJobs(null, null, null, null, true, null);
        $this->assertTrue($response === null);
    }

    public function testGetJobsWithUpdateFlagFalseAndCountNull() {
        $fetcherMock = $this->createMock(JobsFetcher::class);
        $repositoryMock = $this->createMock(JobsRepository::class);
        $repositoryMock->method("getJobsFromDb")->willReturn($this->returnObject);
        $jobFetcher = new JobsService($fetcherMock, $repositoryMock);
        $response = $jobFetcher -> getJobs(null, null, null, null, false, null);
        $this->assertTrue($response === null);
    }

    public function testGetJobsWithUpdateFlagFalseAndCountNotNull() {
        $fetcherMock = $this->createMock(JobsFetcher::class);
        $repositoryMock = $this->createMock(JobsRepository::class);
        $repositoryMock->method("getJobsFromDb")->willReturn($this->returnObject);
        $repositoryMock->method("countJobs")->willReturn(223);
        $jobFetcher = new JobsService($fetcherMock, $repositoryMock);
        $response = $jobFetcher -> getJobs(null, null, null, null, false, null);
        $this->assertTrue($response !== null);
        $this->assertEquals($this->returnObject, $response);

    }
}
