<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;

class WatiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('wati', function () {
            return new class {
                protected $endpoint;
                protected $token;

                public function __construct()
                {
                    $this->endpoint = config('wati.endpoint');
                    $this->token = config('wati.token');
                }

                public function sendTemplateMessage(string $whatsappNumber, string $templateName, string $broadcastName, array $parameters)
                {
                    $response = Http::withToken($this->token)
                    ->post($this->endpoint . '/api/v1/sendTemplateMessage?whatsappNumber=' . $whatsappNumber,
                    [
                        'template_name' => $templateName,
                        'broadcast_name' => $broadcastName,
                        'parameters' => $parameters,
                    ]);

                    return $response;
                }

                public function sendTemplateMessages(string $templateName, string $broadcastName, array $receivers)
                {
                    $response = Http::withToken($this->token)
                    ->post($this->endpoint . '/api/v1/sendTemplateMessages',
                    [
                        'template_name' => $templateName,
                        'broadcast_name' => $broadcastName,
                        'receivers' => $receivers,
                    ]);

                    return $response;
                }

                public function addContact(string $whatsappNumber, string $name, array $customParams = [])
                {
                    $response = Http::withToken($this->token)
                    ->post($this->endpoint . '/api/v1/addContact/' . $whatsappNumber, [
                        'name' => $name,
                        'customParams' => $customParams,
                    ]);

                    return $response->json();
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/wati.php' => config_path('wati.php'),
        ], 'wati-config');
    }
}
