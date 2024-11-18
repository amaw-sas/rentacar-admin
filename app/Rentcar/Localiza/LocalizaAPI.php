<?php

namespace App\Rentcar\Localiza;


use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class LocalizaAPI{

    protected $username;
    protected $password;
    protected $endpoint;
    protected $requestorID;
    protected $token;
    protected $namespace = "http://www.opentravel.org/OTA/2003/05";

    public function __construct(){
        $this->endpoint = config('localiza.endpoint');
        $this->username = config('localiza.username');
        $this->password = config('localiza.password');
        $this->requestorID = config('localiza.requestorID');
        $this->token = config('localiza.token');
    }

    /**
     * connect to Localiza API and get data
     */
    public function callAPI($soapAction, $content){
        try{
            $response = Http::withBasicAuth($this->username, $this->password)
            ->withBody($content, "text/xml")
            ->withHeaders([
                'SOAPAction'    =>  $soapAction,
            ])
            ->accept('text/xml')
            ->post($this->endpoint);

            if($response->clientError()) abort($response->status(), __('localiza.client_error'));
            if($response->serverError()) abort($response->status(), __('localiza.server_error'));

            if($response->successful()) return $response;
        } catch (ConnectionException $th) {
            abort(500, __('localiza.connection_timeout'));
        }
    }

    /**
     * return agency identification data like id and token
     *
     * @return array
     */
    public function getAgencyIdentificationData() : array {
        return [
            'requestor_id'  => $this->requestorID,
            'token'         => $this->token,
        ];
    }


}
