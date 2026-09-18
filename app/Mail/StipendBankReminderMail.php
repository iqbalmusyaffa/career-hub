<?php

namespace App\Mail;

use App\Models\InternshipStipendDisbursement;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StipendBankReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $stipend;
    public $senderName;
    public $senderRole;

    public function __construct(InternshipStipendDisbursement $stipend, string $senderName = 'Tim HR', string $senderRole = 'HR')
    {
        $this->stipend = $stipend;
        $this->senderName = $senderName;
        $this->senderRole = $senderRole;
    }

    public function build()
    {
        return $this->subject("[PENTING] Pengingat Kelengkapan Rekening Uang Saku Magang - {$this->stipend->period_label}")
                    ->markdown('emails.stipends.bank_reminder');
    }
}
