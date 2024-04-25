<?php

namespace App\Rentcar;

use Illuminate\Support\Str;

class Whatsapp {

    /**
     * generate a whatsapp link from a phone and message
     *
     * @param string $phone
     * @param string $message
     *
     * @return string
     */
    static public function generateLink(string $phone, string $message = null) : string {

        $params = [
            'l' =>  'es',
        ];

        if($message)
            $params['text'] = $message;

        $phone = Str::of($phone)->trim()->remove(' ')->remove('+');

        return "https://wa.me/{$phone}?" . http_build_query($params, "", '&', PHP_QUERY_RFC3986);
    }
}
