<?php

namespace Tests\Feature;

use App\Services\MailServices\Mailer;
use App\Services\MailServices\MailManager;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MailerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /**
     * @doesNotPerformAssertions
     */

    /** @test  */
    public function testCodeWithoutUsingAssertions()
    {
        $this->expectNotToPerformAssertions();

        $container = Container::getInstance();
        $userManager = $container->make(MailManager::class);
        $userManager->register('kokodan222@gmail.com', 'email');


    }
}
