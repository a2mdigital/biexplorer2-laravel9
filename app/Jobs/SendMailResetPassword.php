<?php

namespace App\Jobs;

use Throwable;
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
    private $locale;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($host, $token, $subdomain, $email, $locale)
    {
        $this->host = $host;
        $this->token = $token;
        $this->subdomain = $subdomain;
        $this->email = $email;
        $this->locale = $locale;
    }

    /**
     * Execute the job.
     * Para o Job funcionar o Supervisor do servidor deveráe estar rodando
     * o comando php artisan queue:work para ficar verificando as filas de e-mail
     * o endereço do supervisor é: /etc/supervisord.conf
     * restar do supervisor: systemctl restart supervisord
     * @return void
     */
    public function handle()
    {
       
        if($this->locale == 'pt-BR' || $this->locale == 'pt_BR' || $this->locale == 'pt-PT' || $this->locale == 'pt_PT'){
            Mail::send('pages.auth.password-email', ['host' => $this->host, 'token' => $this->token, 'now' => Carbon::now()], function($message){
                $message->from('biexplorer@'.$this->subdomain.'.com.br','Bi Explorer');
                $message->to($this->email);
                $message->subject('Redefinição de Senha');
            });
        }else{
            Mail::send('pages.auth.password-email-en', ['host' => $this->host, 'token' => $this->token, 'now' => Carbon::now()], function($message){
                $message->from('biexplorer@'.$this->subdomain.'.com','Bi Explorer');
                $message->to($this->email);
                $message->subject('Password Reset');
            });
        }
       
    }

    public function failed(Throwable $e){

    }
}
