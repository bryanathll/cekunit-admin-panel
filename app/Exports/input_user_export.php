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
use Maatwebsite\Excel\Concerns\WithMapping;

class input_user_export extends StringValueBinder implements 
    FromQuery, 
    WithHeadings, 
    WithMapping, 
    WithChunkReading, 
    ShouldQueue,
    WithCustomValueBinder
{
    protected $sortColumn;
    protected $sortDirection;

    public function __construct($sortColumn = 'id', $sortDirection = 'asc')
    {
        $this->sortColumn = $sortColumn;
        $this->sortDirection = $sortDirection;
    }

    public function query()
    {
        return input_user::orderBy($this->sortColumn, $this->sortDirection);
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

    public function map($row): array
    {
        static $no = 1;
        return [
            $no++,
            $row->created_at,
            $row->userID,
            $row->nopol,
            $row->lokasi,
            $row->ForN,
            $row->nama,
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }
    public function bindValue(Cell $cell, $value)
    {
        if (is_numeric($value)) {
            $cell->setValueExplicit($value, DataType::TYPE_NUMERIC);
            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}