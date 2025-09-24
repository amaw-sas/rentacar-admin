<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

use Illuminate\Support\Facades\Http;
use App\Providers\WatiServiceProvider;
use Mockery;
use Mockery\MockInterface;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Fake Wati HTTP responses to prevent real API calls
        $wati_url = config('wati.endpoint');
        Http::fake([
            "$wati_url*" => Http::response(['result' => true], 200),
        ]);

   }

}
