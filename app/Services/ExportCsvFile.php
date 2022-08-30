<?php
declare (strict_types = 1);

namespace App\Services;

use App\Jobs\SvcFile;
use App\Events\DownloadExportCsvFile;

class ExportCsvFile
{
    public static function export(string $type, int $id){
        event(new DownloadExportCsvFile('start'));
        sleep(5);
        dispatch(new SvcFile($type, $id));
        event(new DownloadExportCsvFile('done'));
    }
}