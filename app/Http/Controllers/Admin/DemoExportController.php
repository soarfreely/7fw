<?php


namespace App\Http\Controllers\Admin;


use App\Exports\DemoExport;
use App\Models\AdminModel;
use App\Traits\ExportTraits;

class DemoExportController extends AdminController
{
    use ExportTraits;

    public function demo()
    {
        $filename = 'demo-user.xlsx';
        (new DemoExport(AdminModel::query()))->store($filename, 'local');

        $this->export($filename);
    }

    public function test()
    {
        $filename = 'test-user.xlsx';

        return (new DemoExport(AdminModel::query()))->download($filename);
    }
}
