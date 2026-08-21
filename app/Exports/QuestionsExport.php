<?php

namespace App\Exports;

use App\Models\JobTest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class QuestionsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $jobId;

    public function __construct(int $jobId)
    {
        $this->jobId = $jobId;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $test = JobTest::with('questions')->where('job_id', $this->jobId)->first();

        if ($test && $test->questions->count() > 0) {
            return $test->questions;
        }

        // Default sample data for empty template
        return collect([
            (object)[
                'question_text' => 'Berapakah hasil dari 15 + 25?',
                'option_a' => '30',
                'option_b' => '35',
                'option_c' => '40',
                'option_d' => '45',
                'correct_option' => 'c',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'Pertanyaan',
            'Pilihan A',
            'Pilihan B',
            'Pilihan C',
            'Pilihan D',
            'Kunci Jawaban (a/b/c/d)',
        ];
    }

    public function map($row): array
    {
        return [
            $row->question_text,
            $row->option_a,
            $row->option_b,
            $row->option_c,
            $row->option_d,
            strtolower($row->correct_option),
        ];
    }
}
