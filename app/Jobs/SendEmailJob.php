<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\UserEmail;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    protected $publication;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $publication)
    {
        $this->user = $user;
        $this->publication = $publication;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new UserEmail($this->publication);
        Mail::to($this->user)->send($email);
    }
}
