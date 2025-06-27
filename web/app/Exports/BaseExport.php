<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Contracts\Support\Responsable;

class BaseExport implements FromCollection, WithTitle, Responsable
{
    use Exportable;

    private $fileName = 'Export.xlsx';

    public function title(): string
    {
        return mb_substr($this->title, 0, Worksheet::SHEET_TITLE_MAXIMUM_LENGTH);
    }

    public function collection()
    {
        return collect($this->data);
    }

    // Excel下载文件名
    public function setFileName($fileName)
    {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($ext == 'xls' || $ext == 'xlsx' ) {
            $this->fileName = $fileName;
        } else {
            $this->fileName = $fileName.'.xlsx';
        }

        return $this;
    }

    // Sheet工作簿名称
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    // Sheet的数据
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
