<?php


namespace App\Exports;


use App\Exports\Concerns\ExportByQuery;
use Illuminate\Database\Query\Builder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DemoExport extends ExportByQuery
{

    /**
     * @return Builder|null
     * Date: 2020/9/4 Time: 下午1:27
     */
    public function query()
    {
        return $this->exportQuery;
    }

    function widths(): array
    {
        return [
            15,
            15,
            15,
            15,
        ];
    }


    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_NUMBER,
        ];
    }

    public function headings(): array
    {
        return [
            '序号',
            'ID',
            '姓名',
            'E-mail',
        ];
    }

    public function map($row): array
    {
        return [
            $this->index++,
            $row['id'],
            $row['name'],
            $row['email'],
        ];
    }

}
