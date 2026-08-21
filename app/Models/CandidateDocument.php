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
            'ijazah' => '📜 Ijazah Pendidikan',
            'transkrip' => '📊 Transkrip Nilai',
            'ktp' => '🆔 KTP / Kartu Identitas',
            'skck' => '🛡️ SKCK Kepolisian',
            'certificate' => '🏅 Sertifikasi Keahlian',
            'portfolio' => '🎨 Portofolio Karya',
            default => '📄 Berkas Pendukung',
        };
    }

    public function getTypeBadgeColorAttribute()
    {
        return match ($this->document_type) {
            'ijazah' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'transkrip' => 'bg-blue-50 text-blue-700 border-blue-200',
            'ktp' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'skck' => 'bg-amber-50 text-amber-800 border-amber-200',
            'certificate' => 'bg-purple-50 text-purple-700 border-purple-200',
            'portfolio' => 'bg-rose-50 text-rose-700 border-rose-200',
            default => 'bg-slate-100 text-slate-700 border-slate-200',
        };
    }
}
