<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case PENDING = 'pending';
    case SCREENING = 'screening';
    case PROCESSING = 'processing';
    case REVIEWED = 'reviewed';
    case TEST = 'test';
    case INTERVIEW = 'interview';
    case INTERVIEW_HR = 'interview_hr';
    case INTERVIEW_USER = 'interview_user';
    case BACKGROUND_CHECK = 'background_check';
    case OFFERED = 'offered';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu Review',
            self::SCREENING => 'Screening HR',
            self::PROCESSING => 'Diproses',
            self::REVIEWED => 'Review Berkas',
            self::TEST => 'Tes Online',
            self::INTERVIEW, self::INTERVIEW_HR => 'Wawancara HR',
            self::INTERVIEW_USER => 'Wawancara User',
            self::BACKGROUND_CHECK => 'Background Check',
            self::OFFERED => 'Penawaran (Offering)',
            self::ACCEPTED => 'Diterima (Hired)',
            self::REJECTED => 'Ditolak',
        };
    }

    public function shortLabel(): string
    {
        return match($this) {
            self::PENDING => 'Review',
            self::SCREENING => 'Screening',
            self::PROCESSING => 'Diproses',
            self::REVIEWED => 'Review Berkas',
            self::TEST => 'Tes Online',
            self::INTERVIEW, self::INTERVIEW_HR => 'Interview HR',
            self::INTERVIEW_USER => 'Interview User',
            self::BACKGROUND_CHECK => 'Bg Check',
            self::OFFERED => 'Offering',
            self::ACCEPTED => 'Hired',
            self::REJECTED => 'Ditolak',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'bg-amber-100 text-amber-800 border-amber-200',
            self::SCREENING => 'bg-blue-100 text-blue-800 border-blue-200',
            self::PROCESSING, self::REVIEWED => 'bg-sky-100 text-sky-800 border-sky-200',
            self::TEST => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            self::INTERVIEW, self::INTERVIEW_HR => 'bg-purple-100 text-purple-800 border-purple-200',
            self::INTERVIEW_USER => 'bg-fuchsia-100 text-fuchsia-800 border-fuchsia-200',
            self::BACKGROUND_CHECK => 'bg-teal-100 text-teal-800 border-teal-200',
            self::OFFERED => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::ACCEPTED => 'bg-emerald-600 text-white font-black',
            self::REJECTED => 'bg-rose-100 text-rose-800 border-rose-200',
        };
    }
}
