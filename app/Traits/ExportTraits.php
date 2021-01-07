<?php


namespace App\Traits;


trait ExportTraits
{
    /**
     * @param $filename
     * @param string $disk
     */
    public function export($filename, $disk = 'local')
    {
        $file = config("filesystems.disks.$disk.root") . DIRECTORY_SEPARATOR . $filename;
        if (file_exists($file)) {
            ob_end_clean();
            header("Content-Disposition:  attachment;  filename=" . $filename);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Pragma: public'); // HTTP/1.0
            header('Cache-Control: cache, must-revalidate');

            header('Expires: 0');
            header('Content-Encoding: none');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Description: File Transfer');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit();
        }
    }
}
