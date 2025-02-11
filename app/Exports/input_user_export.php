<?php

namespace App\Exports;

use App\Models\input_user;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Illuminate\Contracts\Queue\ShouldQueue;

class input_user_export implements FromQuery, WithHeadings, WithChunkReading, ShouldQueue
{
    protected $sortColumn;
    protected $sortDirection;

    public function query()
    {
        return input_user::orderBy('id', 'asc');
    }

    public function headings(): array
    {
        return [
            'no',
            'created_at',
            'userID',
            'nopol',
            'lokasi',
            'ForN',
            'nama',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        // Force format semua cell sebagai text
        $cell->setValueExplicit($value, DataType::TYPE_STRING);
        return true;
    }
    
    public function chunkSize(): int
    {
        return 1000; // Proses data per 1000 baris
    }
}