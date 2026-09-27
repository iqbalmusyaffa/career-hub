<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\HasEncryptedId;

class CandidateDocument extends Model
{
    use HasFactory, HasEncryptedId;

    protected $fillable = [
        'user_id',
        'document_type',
        'title',
        'file_path',
        'file_size',
        'file_extension',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size ?: 0;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->document_type) {
            'cv' => '📄 Curriculum Vitae (CV)',
            'ktp' => '🆔 KTP / Kartu Identitas',
            'ijazah' => '📜 Ijazah Pendidikan',
            'transkrip', 'transcript' => '📊 Transkrip Nilai',
            'certificate' => '🏅 Sertifikasi Keahlian',
            'portfolio' => '🎨 Portofolio Karya',
            'skck' => '🛡️ SKCK Kepolisian',
            'health_certificate' => '🏥 Surat Keterangan Sehat',
            'cover_letter' => '✉️ Surat Lamaran Kerja',
            'consent_letter' => '📝 Surat Pernyataan',
            default => '📄 ' . (ucwords(str_replace(['_', '-'], ' ', $this->document_type ?: 'Berkas Pendukung'))),
        };
    }

    public function getTypeBadgeColorAttribute()
    {
        return match ($this->document_type) {
            'cv' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-950/80 dark:text-blue-300 dark:border-blue-800',
            'ktp' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/80 dark:text-emerald-300 dark:border-emerald-800',
            'ijazah' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/80 dark:text-indigo-300 dark:border-indigo-800',
            'transkrip', 'transcript' => 'bg-sky-50 text-sky-800 border-sky-200 dark:bg-sky-950/80 dark:text-sky-300 dark:border-sky-800',
            'certificate' => 'bg-purple-50 text-purple-700 border-purple-200 dark:bg-purple-950/80 dark:text-purple-300 dark:border-purple-800',
            'portfolio' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/80 dark:text-rose-300 dark:border-rose-800',
            'skck' => 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-950/80 dark:text-amber-300 dark:border-amber-800',
            'health_certificate' => 'bg-teal-50 text-teal-700 border-teal-200 dark:bg-teal-950/80 dark:text-teal-300 dark:border-teal-800',
            'cover_letter' => 'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-950/80 dark:text-violet-300 dark:border-violet-800',
            'consent_letter' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
            default => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
        };
    }
}
