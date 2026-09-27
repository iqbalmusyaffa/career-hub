<?php

namespace App\Mail;

use App\Models\CandidateTestResult;
use App\Models\Job;
use App\Models\JobTest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CandidateTestResultMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $job;
    public $test;
    public $result;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Job $job, JobTest $test, CandidateTestResult $result)
    {
        $this->user = $user;
        $this->job = $job;
        $this->test = $test;
        $this->result = $result;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $jobTitle = $this->job ? $this->job->title : 'Lowongan Pekerjaan';
        $companyName = $this->job ? ($this->job->company_name ?: 'Perusahaan Mitra') : 'Perusahaan Mitra';
        $subject = $this->result->passed
            ? "🎉 Hasil Ujian Online: LULUS Seleksi - {$jobTitle} ({$companyName})"
            : "Hasil Ujian Seleksi Online: {$jobTitle} - {$companyName}";

        return $this->subject($subject)
                    ->markdown('emails.candidate_test_result');
    }
}
