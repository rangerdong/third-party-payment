<?php

namespace App\Jobs;

use App\Models\PlatUserTmp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DoRegisterEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userTmp;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\PlatUserTmp $platUserTmp
     */
    public function __construct(PlatUserTmp $platUserTmp)
    {
        //
        $this->userTmp = $platUserTmp;
    }

    /**
     * Execute the job.
     *
     * @param \Illuminate\Mail\Mailer $mailer
     *
     * @return void
     */
    public function handle(Mailer $mailer)
    {
        //
        if ($this->attempts() > 2) {
            $this->delete();
        } else {
            $mailer->send('emails.buz.register', ['url' => route('buz.doregister') .'?token='.$this->userTmp->token], function ($m) {
                $m->to($this->userTmp->username)->subject(config('sms.sign').'平台注册激活邮件');
            });
        }
    }
}
