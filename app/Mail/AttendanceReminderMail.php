<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AttendanceReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $type;
    public string $title;
    public string $messageContent;
    public string $actionUrl;
    public string $actionText;
    public string $dateFormatted;
    public array $extraInfo;

    /**
     * Create a new message instance.
     */
    public function __construct(
        User $user,
        string $type,
        string $title,
        string $messageContent,
        string $actionUrl,
        string $actionText,
        string $dateFormatted,
        array $extraInfo = []
    ) {
        $this->user = $user;
        $this->type = $type;
        $this->title = $title;
        $this->messageContent = $messageContent;
        $this->actionUrl = $actionUrl;
        $this->actionText = $actionText;
        $this->dateFormatted = $dateFormatted;
        $this->extraInfo = $extraInfo;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $subject = match ($this->type) {
            'morning_checkin' => "⏰ [Presensi Pagi] Pengingat Kehadiran Magang ({$this->dateFormatted})",
            'afternoon_logbook' => "📝 [Logbook Harian] Pengingat Mengisi Aktivitas Kerja ({$this->dateFormatted})",
            'mentor_pending' => "📋 [Pengingat Mentor] Terdapat Logbook Magang Menunggu Verifikasi",
            default => "⏰ Pengingat Presensi & Logbook Magang",
        };

        return $this->subject($subject)
            ->markdown('emails.attendance_reminder');
    }
}
