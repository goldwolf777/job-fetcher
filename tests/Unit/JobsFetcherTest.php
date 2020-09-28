<?php

namespace Tests\Unit;

use App\Http\Services\JobsFetcherImpl;
use PHPUnit\Framework\TestCase;

class JobsFetcherTest extends TestCase {

    public function testAbleToRetrieveDataFromApi() {
        $jobFetcher = new JobsFetcherImpl();
        $response = $jobFetcher -> getJobs();
        $this->assertTrue(is_array($response));
        $this->assertTrue($response[0]->{"otsikko"} !== null);
    }
}
