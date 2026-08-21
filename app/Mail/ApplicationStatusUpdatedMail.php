<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $application;
    public $statusLabel;

    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->statusLabel = $application->status_label ?? ucfirst($application->status);
    }

    public function build()
    {
        $jobTitle = $this->application->job ? $this->application->job->title : 'Lowongan Kerja';
        $companyName = $this->application->job ? ($this->application->job->company_name ?: 'Perusahaan') : 'Perusahaan';

        return $this->subject("Update Status Lamaran: {$jobTitle} di {$companyName}")
                    ->markdown('emails.applications.status_updated');
    }
}
