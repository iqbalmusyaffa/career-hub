<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\JobTest;
use App\Models\TestQuestion;
use App\Exports\QuestionsExport;
use App\Imports\QuestionsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class JobTestController extends Controller
{
    /**
     * Show test configuration and question editor for a job.
     */
    public function edit(Job $job)
    {
        $test = JobTest::with('questions')->firstOrCreate(
            ['job_id' => $job->id],
            [
                'title' => 'Tes Psikotes & Potensi Kerja: ' . $job->title,
                'category' => 'psikotes',
                'description' => 'Petunjuk: Pilihlah satu jawaban yang paling tepat untuk setiap soal penalaran logika, deret angka, dan analogi verbal berikut.',
                'duration_minutes' => 30,
                'passing_score' => 70,
                'is_active' => true,
            ]
        );

        // Auto-seed standard Psikotes questions if empty
        if ($test->questions->count() === 0) {
            $this->seedStandardPsikotesQuestions($test);
            $test->load('questions');
        }

        return view('admin.jobs.test_editor', compact('job', 'test'));
    }

    /**
     * Save/update test settings and questions.
     */
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'test_mode' => 'required|in:internal,external',
            'external_url' => 'nullable|url|required_if:test_mode,external',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1|max:180',
            'passing_score' => 'required|integer|min:10|max:100',
            'questions' => 'nullable|array',
            'questions.*.question_text' => 'nullable|string',
            'questions.*.option_a' => 'nullable|string',
            'questions.*.option_b' => 'nullable|string',
            'questions.*.option_c' => 'nullable|string',
            'questions.*.option_d' => 'nullable|string',
            'questions.*.correct_option' => 'nullable|in:a,b,c,d',
        ]);

        $test = JobTest::firstOrCreate(['job_id' => $job->id]);
        
        $filePath = $test->file_path;
        if ($request->hasFile('pdf_file')) {
            $request->validate([
                'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
            ]);
            if ($filePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($filePath);
            }
            $companyFolder = 'company_tests/' . \Illuminate\Support\Str::slug($job->company_name ?: 'company');
            $filePath = $request->file('pdf_file')->store($companyFolder, 'public');
        }

        $test->update([
            'title' => $request->title,
            'category' => $request->category,
            'test_mode' => $request->test_mode ?? 'internal',
            'external_url' => $request->external_url,
            'file_path' => $filePath,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'passing_score' => $request->passing_score,
            'is_active' => $request->has('is_active'),
        ]);

        // Sync Questions
        $test->questions()->delete();

        if ($request->has('questions') && is_array($request->questions)) {
            foreach ($request->questions as $q) {
                if (!empty($q['question_text'])) {
                    $test->questions()->create([
                        'question_text' => $q['question_text'],
                        'option_a' => $q['option_a'],
                        'option_b' => $q['option_b'],
                        'option_c' => $q['option_c'],
                        'option_d' => $q['option_d'],
                        'correct_option' => strtolower($q['correct_option']),
                        'points' => 10,
                    ]);
                }
            }
        }

        return back()->with('success', 'Tes Psikotes & Soal Seleksi berhasil disimpan!');
    }

    /**
     * Export existing questions or template using Maatwebsite Excel.
     */
    public function export(Job $job)
    {
        $fileName = 'templat_soal_tes_' . str_replace(' ', '_', strtolower($job->title)) . '.csv';

        return Excel::download(new QuestionsExport($job->id), $fileName, \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Import questions from uploaded file using Maatwebsite Excel.
     */
    public function import(Request $request, Job $job)
    {
        $request->validate([
            'csv_file' => 'required|file|max:5120',
        ]);

        $test = JobTest::firstOrCreate(['job_id' => $job->id]);

        try {
            Excel::import(new QuestionsImport($test->id), $request->file('csv_file'));
            return back()->with('success', 'Berhasil mengimpor soal dari file Excel / CSV (Maatwebsite Excel)!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    /**
     * Seed standard Psikotes questions.
     */
    private function seedStandardPsikotesQuestions(JobTest $test)
    {
        $questions = [
            [
                'question_text' => 'Deret Angka: 2, 4, 8, 16, 32, ... Berapakah angka selanjutnya?',
                'option_a' => '48',
                'option_b' => '64',
                'option_c' => '50',
                'option_d' => '60',
                'correct_option' => 'b',
            ],
            [
                'question_text' => 'Analogi Kata: KUCING : MEOONG = ANJING : ...',
                'option_a' => 'GONGGONG',
                'option_b' => 'RINGKIK',
                'option_c' => 'LENGUH',
                'option_d' => 'KICAU',
                'correct_option' => 'a',
            ],
            [
                'question_text' => 'Deret Angka: 100, 95, 85, 70, 50, ... Berapakah angka selanjutnya?',
                'option_a' => '30',
                'option_b' => '25',
                'option_c' => '20',
                'option_d' => '35',
                'correct_option' => 'b',
            ],
            [
                'question_text' => 'Penalaran Logika: Semua karyawan PT Merdeka hadir tepat waktu. Budi adalah karyawan PT Merdeka. Kesimpulan yang tepat adalah...',
                'option_a' => 'Budi mungkin terlambat',
                'option_b' => 'Budi hadir tepat waktu',
                'option_c' => 'Budi adalah manajer',
                'option_d' => 'Budi tidak hadir',
                'correct_option' => 'b',
            ],
            [
                'question_text' => 'Sinonim Kata: KREDIBEL sama artinya dengan...',
                'option_a' => 'Dapat dipercaya',
                'option_b' => 'Penuh kecurangan',
                'option_c' => 'Sangat meragukan',
                'option_d' => 'Sangat mahal',
                'correct_option' => 'a',
            ],
        ];

        foreach ($questions as $q) {
            $test->questions()->create($q);
        }
    }
}
