<?php

return [
    'endpoint'  =>  env('LOCALIZA_API_ENDPOINT',"test"),
    'username'  =>  env('LOCALIZA_API_USERNAME',"test"),
    'password'  =>  env('LOCALIZA_API_PASSWORD',"test"),
    'token'  =>  env('LOCALIZA_API_TOKEN',"test"),
    'requestorID'  =>  env('LOCALIZA_API_REQUESTOR_ID',"test"),
    'reservationEmailAddress' => env('LOCALIZA_RESERVATION_EMAIL_ADDRESS',"test"),
    'bccEmailAddress' => env('LOCALIZA_RESERVATION_BCC_EMAIL_ADDRESS',"test"),
    'totalCoveragePriceLowGamma' => env('LOCALIZA_TOTAL_COVERAGE_PRICE_LOW_GAMMA',42000),
    'totalCoveragePriceHighGamma' => env('LOCALIZA_TOTAL_COVERAGE_PRICE_HIGH_GAMMA',55000),
];
