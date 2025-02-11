<?php

namespace App\Exports;

use App\Models\cekunit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Illuminate\Contracts\Queue\ShouldQueue;

class CekUnitExport extends StringValueBinder implements FromQuery, WithHeadings, WithChunkReading, WithCustomValueBinder, ShouldQueue
{
    public function query()
    {
        return cekunit::orderBy('no', 'asc');
    }

    public function headings(): array
    {
        return [
            'no',
            'no_perjanjian',
            'nama_nasabah',
            'nopol',
            'coll',
            'pic',
            'kategori',
            'jto',
            'no_Rangka',
            'no_Mesin',
            'merk',
            'type',
            'warna',
            'status',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        // Paksa semua nilai menjadi teks
        $cell->setValueExplicit($value, DataType::TYPE_STRING);
        return true;
    }

    public function chunkSize(): int
    {
        return 1000; // Gunakan ukuran yang lebih aman
    }
}
