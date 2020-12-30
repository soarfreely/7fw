<?php

namespace App\Exports;


use Closure;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

abstract class ExportByQuery implements FromQuery, WithHeadings, WithMapping,  WithEvents, WithColumnFormatting
{
    use Exportable;

    /**
     * @var null
     */
    protected  $exportQuery = null;

    /**
     * 表头
     *
     * @var array
     */
    private $tableHead = [];

    /**
     * 序号
     *
     * @var int
     */
    protected  $index = 1;

    /**
     * ExportByQuery constructor.
     * @param $query
     */
    public function __construct($query)
    {
        $this->exportQuery = $query;
    }

    /**
     * 指定列宽
     *
     * User: zhangxiang_php@vchangyi.com
     * @return array
     * Date: 2020/9/4 Time: 下午3:27
     */
    abstract function widths() : array ;

    /**
     * User: zhangxiang_php@vchangyi.com
     * @return array|Closure[]
     * Date: 2020/9/4 Time: 下午2:51
     */
    public function registerEvents(): array
    {
        // 总列数
        $totalColumn = $this->columnNumber();

        // 总行数:数据行数 + 表头(默认一行)
        $totalRow = $this->totalRow();
        $pColumn = '';

        return [
            AfterSheet::class => function(AfterSheet $event) use ($totalColumn, $totalRow, &$pColumn) {
                for ($index = 0; $index < $totalColumn; $index++) {
                    $pColumn = $this->column($index);
                    $widths = $this->widths();

                    if (isset($widths[$index]) && intval($widths[$index]) > 0) {
                        $width  = $widths[$index];
                        $event->sheet->getDelegate()->getColumnDimension($pColumn)->setWidth($width);
                    }
                }

                $pCellCoordinate = "A1:{$pColumn}{$totalRow}";

                $event->sheet->getDelegate()->getStyle($pCellCoordinate)->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setVertical(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }

    /**
     * 获取对应列   ’A'
     *
     * @param int $columnNumber
     * User: zhangxiang_php@vchangyi.com
     * @return string
     * Date: 2020/9/4 Time: 下午3:21
     */
    public function column(int $columnNumber)
    {
        $rawLetter = config('letters.decode_data');
        $letters = [];

        $loop = ceil($columnNumber / 25);
        for ($index = 0; $index < $loop - 1; $index++) {
            array_push($letters, $rawLetter[$index]);
        }

        $prefixLetter = implode('', $letters);

        $suffixLetterIndex = $columnNumber % 25;
        $suffixLetter = $rawLetter[$suffixLetterIndex];

        return $prefixLetter . $suffixLetter;
    }

    /**
     * 记录的数量
     *
     * User: zhangxiang_php@vchangyi.com
     * @return int
     * Date: 2020/9/4 Time: 下午3:39
     */
    public function rowNumber() : int
    {
        return $this->query()->count();
    }

    /**
     * 列的数量
     *
     * User: zhangxiang_php@vchangyi.com
     * @return int
     * Date: 2020/9/4 Time: 下午3:44
     */
    public function columnNumber()
    {
        return count($this->headings());
    }

    /**
     * 总行数，包含表头
     *
     * User: zhangxiang_php@vchangyi.com
     * Date: 2020/9/8 Time: 下午2:21
     */
    public function totalRow():int
    {
        // 总行数:数据行数 + 表头(默认一行)
        return $this->rowNumber() + (empty($this->headings()) ? 0 : 1);
    }
}
