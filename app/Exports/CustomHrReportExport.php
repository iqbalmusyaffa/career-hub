<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Collection;

class CustomHrReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Collection $data;
    protected array $selectedColumns;

    public function __construct(Collection $data, array $selectedColumns)
    {
        $this->data = $data;
        $this->selectedColumns = $selectedColumns;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return array_values($this->selectedColumns);
    }

    public function map($row): array
    {
        $result = [];
        $keys = array_keys($this->selectedColumns);

        foreach ($keys as $key) {
            $result[] = data_get($row, $key, '-');
        }

        return $result;
    }
}
