<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendMailResetPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 2;
    private $host;
    private $token;
    private $subdomain;
    private $email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($host, $token, $subdomain, $email)
    {
        $this->host = $host;
        $this->token = $token;
        $this->subdomain = $subdomain;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send('pages.auth.password-email', ['host' => $this->host, 'token' => $this->token, 'now' => Carbon::now()], function($message){
            $message->from('biexplorer@'.$this->subdomain.'.com.br','Bi Explorer');
            $message->to($this->email);
            $message->subject('Redefinição de Senha');
        });
    }
}
