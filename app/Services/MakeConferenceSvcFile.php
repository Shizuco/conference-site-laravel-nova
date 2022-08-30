<?php
declare (strict_types = 1);

namespace App\Services;

use App\Models\Conference;
use App\Services\MakeCsvFileInterface;
use App\Services\CsvFileAttributes;

class MakeConferenceSvcFile implements MakeCsvFileInterface
{
    public static function getFile(int $id = 0)
    {
        $fileName = 'conferences.csv';
        $headers = CsvFileAttributes::getHeaders($fileName);

        $callback = function () {
            CsvFileAttributes::makeContent('conference', 0);
        };
        $response = [$callback, 200, $headers];
        return $response;
    }

    public static function sendFile(int $id = 0)
    {
        $file = MakeConferenceSvcFile::getFile();
        return response()->stream($file[0], $file[1], $file[2]);
    }
}
