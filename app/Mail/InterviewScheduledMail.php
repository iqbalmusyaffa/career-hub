<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function build()
    {
        $jobTitle = $this->application->job ? $this->application->job->title : 'Lowongan Kerja';
        $companyName = $this->application->job ? ($this->application->job->company_name ?: 'Perusahaan') : 'Perusahaan';

        return $this->subject("🗓️ Undangan Wawancara: {$jobTitle} di {$companyName}")
                    ->markdown('emails.applications.interview_scheduled');
    }
}
