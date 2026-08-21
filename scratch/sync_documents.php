<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CandidateProfile;
use App\Models\CandidateDocument;
use Illuminate\Support\Facades\Storage;

$profiles = CandidateProfile::all();
$count = 0;

$vaultTitleMap = [
    'cv' => 'Curriculum Vitae (CV)',
    'ktp' => 'KTP / Kartu Identitas',
    'ijazah' => 'Ijazah Pendidikan',
    'transcript' => 'Transkrip Nilai Akademik',
    'certificate' => 'Sertifikat Keahlian',
    'portfolio' => 'Portofolio Berkas Karya',
    'cover_letter' => 'Surat Lamaran Kerja',
    'skck' => 'SKCK Kepolisian',
    'health_certificate' => 'Surat Keterangan Sehat',
    'consent_letter' => 'Surat Persetujuan',
];

$columnsMap = [
    'cv_path' => 'cv',
    'ktp_path' => 'ktp',
    'ijazah_path' => 'ijazah',
    'transcript_path' => 'transcript',
    'certificate_file_path' => 'certificate',
    'portfolio_file_path' => 'portfolio',
    'cover_letter_path' => 'cover_letter',
    'skck_path' => 'skck',
    'health_certificate_path' => 'health_certificate',
    'consent_letter_path' => 'consent_letter',
];

foreach ($profiles as $profile) {
    if (!$profile->user_id) continue;

    foreach ($columnsMap as $col => $type) {
        $path = $profile->$col;
        if ($path && Storage::disk('public')->exists($path)) {
            $docTypeNormalized = match ($type) {
                'transcript' => 'transkrip',
                default => $type,
            };

            $docTitle = $vaultTitleMap[$type] ?? ucfirst($docTypeNormalized);
            $fileSize = Storage::disk('public')->size($path);
            $fileExt = pathinfo($path, PATHINFO_EXTENSION);

            CandidateDocument::updateOrCreate(
                [
                    'user_id' => $profile->user_id,
                    'document_type' => $docTypeNormalized,
                ],
                [
                    'title' => $docTitle,
                    'file_path' => $path,
                    'file_size' => $fileSize,
                    'file_extension' => strtolower($fileExt),
                    'updated_at' => now(),
                ]
            );
            $count++;
        }
    }
}

echo "Successfully synced {$count} existing documents to Candidate Document Vault!\n";
