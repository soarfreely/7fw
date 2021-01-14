<?php

namespace App\Exports\Concerns;


use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;

abstract class ExportByQuery extends AbstractExport implements FromQuery
{
    /**
     * ExportByQuery constructor.
     *
     * @param $query
     */
    public function __construct($query)
    {
        $this->exportQuery = $query;
    }

    /**
     * @return Builder|null
     */
    public function query()
    {
        return $this->exportQuery;
    }

    /**
     * 记录的数量
     *
     * @return int
     */
    public function rowNumber() : int
    {
        return $this->query()->count();
    }
}
