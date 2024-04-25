<?php

namespace Tests\Feature\Localiza;

use App\Rentcar\Localiza\LocalizaAPI;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class LocalizaAPITest extends TestCase
{
    #[Group("localiza")]
    #[Test]
    public function when_client_error_get_abort_400_error(): void {
        Http::fake([
            '*' =>  Http::response('error', 400)
        ]);

        $localiza = new LocalizaAPI();
        $this->assertThrows(fn() => $localiza->callAPI("test","test"), HttpException::class);
    }

    #[Group("localiza")]
    #[Test]
    public function when_server_error_get_abort_500_error(): void {
        Http::fake([
            '*' =>  Http::response('error', 500)
        ]);

        $localiza = new LocalizaAPI();

        $this->assertThrows(fn() => $localiza->callAPI("test","test"), HttpException::class);
    }
}
