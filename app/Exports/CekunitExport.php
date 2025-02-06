<?php

namespace App\Exports;

use App\Models\cekunit;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
// use Maatwebsite\Excel\Concerns\ShouldQueue;

class CekUnitExport implements FromQuery, WithHeadings, WithChunkReading
{
    protected $sortColumn;
    protected $sortDirection;



    public function query()
    {
        return CekUnit::orderBy('no', 'asc');
    }

    public function collection(){
        return CekUnit::orderBy('no', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    $item->no,
                    $item->no_perjanjian,
                    $item->nama_nasabah,
                    $item->nopol,
                    $item->coll,
                    $item->pic,
                    $item->kategori,
                    $item->jto,
                    $item->no_rangka,
                    $item->no_mesin,
                    $item->merk,
                    $item->type,
                    $item->warna,
                    $item->status,
                ];
            })
            ->cursor();
    }

    public function headings(): array
    {
        return [
            'No',
            'No Perjanjian',
            'Nama Nasabah',
            'Nopol',
            'Coll',
            'PIC',
            'Kategori',
            'JTO',
            'No Rangka',
            'No Mesin',
            'Merk',
            'Type',
            'Warna',
            'Status',
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
        return 100000; // Proses data per 1000 baris
    }
}