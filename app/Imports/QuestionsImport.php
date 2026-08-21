<?php

namespace App\Imports;

use App\Models\TestQuestion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class QuestionsImport implements ToModel, WithHeadingRow
{
    protected $jobTestId;

    public function __construct(int $jobTestId)
    {
        $this->jobTestId = $jobTestId;
    }

    /**
     * Map row to model.
     */
    public function model(array $row)
    {
        // Flexible key matching for heading rows
        $questionText = $row['pertanyaan'] ?? $row['question_text'] ?? $row[0] ?? null;
        $optionA = $row['pilihan_a'] ?? $row['option_a'] ?? $row[1] ?? null;
        $optionB = $row['pilihan_b'] ?? $row['option_b'] ?? $row[2] ?? null;
        $optionC = $row['pilihan_c'] ?? $row['option_c'] ?? $row[3] ?? null;
        $optionD = $row['pilihan_d'] ?? $row['option_d'] ?? $row[4] ?? null;
        $correctOpt = strtolower($row['kunci_jawaban_abcd'] ?? $row['kunci_jawaban'] ?? $row['correct_option'] ?? $row[5] ?? 'a');

        if (empty($questionText) || empty($optionA)) {
            return null;
        }

        return new TestQuestion([
            'job_test_id' => $this->jobTestId,
            'question_text' => trim($questionText),
            'option_a' => trim($optionA),
            'option_b' => trim($optionB),
            'option_c' => trim($optionC),
            'option_d' => trim($optionD),
            'correct_option' => in_array($correctOpt, ['a', 'b', 'c', 'd']) ? $correctOpt : 'a',
            'points' => 10,
        ]);
    }
}
