<?php


namespace App\Exports\Concerns;


use Maatwebsite\Excel\Concerns\FromArray;

abstract class ExportByArray extends AbstractExport implements FromArray
{
    /**
     * ExportByArray constructor.
     * @param $array
     */
    public function __construct($array)
    {
        $this->exportArray = $array;
    }

    /**
     * @return array
     */
    public function array(): array
    {
        return $this->exportArray;
    }

    /**
     * 记录的数量
     *
     * @return int
     */
    public function rowNumber() : int
    {
        return count($this->exportArray);
    }
}
