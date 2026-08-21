<?php

namespace App\Mail;

use App\Models\OfferLetter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferLetterSentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $offerLetter;

    public function __construct(OfferLetter $offerLetter)
    {
        $this->offerLetter = $offerLetter;
    }

    public function build()
    {
        $jobTitle = $this->offerLetter->job->title ?? 'Pekerjaan';
        
        $mail = $this->subject("🎉 Selamat! Surat Penawaran Kerja (Offer Letter): {$jobTitle}")
                     ->view('emails.offer_letter_sent');

        if ($this->offerLetter->pdf_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($this->offerLetter->pdf_path)) {
            $mail->attach(storage_path('app/public/' . $this->offerLetter->pdf_path), [
                'as' => "Offer_Letter_{$this->offerLetter->position_title}.pdf",
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
