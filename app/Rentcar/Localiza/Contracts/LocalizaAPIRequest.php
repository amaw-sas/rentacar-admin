<?php

namespace App\Rentcar\Localiza\Contracts;

interface LocalizaAPIRequest {
    public function getData(): array;
    public function getFilledInputXML(): string;
}
