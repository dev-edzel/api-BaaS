<?php

namespace App\Jobs;

use App\Mail\ResetPasswordLinkMailer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessResetPasswordLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, \Illuminate\Foundation\Queue\Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Mail::to($this->data['email'])
            ->send(new ResetPasswordLinkMailer($this->data));
    }
}
