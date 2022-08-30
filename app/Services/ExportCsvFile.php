<?php
declare (strict_types = 1);

namespace App\Services;

use App\Jobs\CsvFile;
use App\Events\DownloadExportCsvFile;

class ExportCsvFile
{
    public static function export(string $type, int $id){
        event(new DownloadExportCsvFile('start'));
        sleep(5);
        dispatch(new CsvFile($type, $id));
        event(new DownloadExportCsvFile('done'));
    }
}