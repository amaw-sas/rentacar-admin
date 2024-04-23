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

        $this->expectException(HttpException::class);
        // $this->expectErrorMessage(__('localiza.client_error'));

        $localiza = new LocalizaAPI();
        $localiza->callAPI("test","test");
    }

    #[Group("localiza")]
    #[Test]
    public function when_server_error_get_abort_500_error(): void {
        Http::fake([
            '*' =>  Http::response('error', 500)
        ]);

        $this->expectException(HttpException::class);
        // $this->expectErrorMessage(__('localiza.server_error'));

        $localiza = new LocalizaAPI();
        $localiza->callAPI("test","test");
    }
}
