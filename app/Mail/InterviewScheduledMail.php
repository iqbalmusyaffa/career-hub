<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $interview;

    public function __construct($applicationOrInterview, ?Interview $interview = null)
    {
        if ($applicationOrInterview instanceof Interview) {
            $this->interview = $applicationOrInterview;
            $this->application = $applicationOrInterview->application ?? Application::find($applicationOrInterview->application_id);
        } elseif ($applicationOrInterview instanceof Application) {
            $this->application = $applicationOrInterview;
            $this->interview = $interview ?? $applicationOrInterview->interview;
        }
    }

    public function build()
    {
        $jobTitle = ($this->application && $this->application->job) ? $this->application->job->title : 'Lowongan Kerja';
        $companyName = ($this->application && $this->application->job) ? ($this->application->job->company_name ?: 'Perusahaan') : 'Perusahaan';

        return $this->subject("🗓️ Undangan Wawancara: {$jobTitle} di {$companyName}")
                    ->markdown('emails.applications.interview_scheduled');
    }
}
