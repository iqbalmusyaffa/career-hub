<?php

namespace App\Mail;

use App\Models\Application;
use App\Models\JobTest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateTestRetakeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $test;
    public $testToken;

    /**
     * Create a new message instance.
     */
    public function __construct(Application $application, JobTest $test, string $testToken)
    {
        $this->application = $application;
        $this->test = $test;
        $this->testToken = $testToken;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $jobTitle = $this->application->job ? $this->application->job->title : 'Lowongan Pekerjaan';
        $companyName = $this->application->job ? ($this->application->job->company_name ?: 'Perusahaan Mitra') : 'Perusahaan Mitra';

        return $this->subject("🔄 Kesempatan Ujian Ulang (Retake): {$jobTitle} - {$companyName}")
                    ->markdown('emails.candidate_test_retake');
    }
}
