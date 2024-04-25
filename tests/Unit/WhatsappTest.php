<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use App\Rentcar\Whatsapp;

class WhatsappTest extends TestCase
{
    #[Group("whatsapp")]
    #[Test]
    public function create_a_whatsapp_link_without_message(): void {

        $whatsappLink = Whatsapp::generateLink("57315121212");

        $this->assertEquals('https://wa.me/57315121212?l=es', $whatsappLink);
    }

    #[Group("whatsapp")]
    #[Test]
    public function create_a_whatsapp_link_with_message(): void {

        $whatsappLink = Whatsapp::generateLink("57315121212","test");

        $this->assertEquals('https://wa.me/57315121212?l=es&text=test', $whatsappLink);
    }

    #[Group("whatsapp")]
    #[Test]
    public function remove_pluses_and_spaces_on_phone(): void {

        $whatsappLink = Whatsapp::generateLink("+57 315 12 12 12","test");

        $this->assertEquals('https://wa.me/57315121212?l=es&text=test', $whatsappLink);
    }
}
