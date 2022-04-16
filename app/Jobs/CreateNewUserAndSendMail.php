<?php

namespace App\Jobs;

use App\Mail\MailAfterRegistration;
use App\Services\MailServices\MailManager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;
use Illuminate\Container\Container;


class CreateNewUserAndSendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $name;
    protected $email;
    public function __construct($name,$email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    public function handle()
    {
        $container = Container::getInstance();
        $userManager = $container->make(MailManager::class);
        $userManager->register($this->name, $this->email);

    }
}
