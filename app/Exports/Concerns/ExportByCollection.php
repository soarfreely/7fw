<?php


namespace App\Exports\Concerns;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

abstract class ExportByCollection extends AbstractExport implements FromCollection
{
    /**
     * ExportByCollection constructor.
     * @param $collection
     */
    public function __construct($collection)
    {
        $this->exportCollection = $collection;
    }

    /**
     * @return Collection|null
     */
    public function collection()
    {
        return $this->exportCollection;
    }

    /**
     * 记录的数量
     *
     * @return int
     */
    public function rowNumber() : int
    {
        return $this->exportCollection->count();
    }
}
