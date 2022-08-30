<?php
declare (strict_types = 1);

namespace App\Services;

use App\Models\Report;
use App\Services\MakeCsvFileInterface;
use App\Services\CsvFileAttributes;

class MakeReportCsvFile implements MakeCsvFileInterface
{
    public static function getFile(int $id)
    {
        $fileName = 'reports.csv';
        $headers = CsvFileAttributes::getHeaders($fileName);

        $callback = function () use ($id){
            CsvFileAttributes::makeContent('report', $id);
        };
        $response = [$callback, 200, $headers];
        return $response;
    }

    public static function sendFile(int $id)
    {
        $file = MakeReportCsvFile::getFile($id);
        return response()->stream($file[0], $file[1], $file[2]);
    }
}
