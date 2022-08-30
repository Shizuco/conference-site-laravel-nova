<?php
declare (strict_types = 1);

namespace App\Services;

use App\Models\User;
use App\Services\MakeCsvFileInterface;
use App\Services\CsvFileAttributes;

class MakeListenerCsvFile implements MakeCsvFileInterface
{

    public static function getFile(int $id)
    {
        $fileName = 'listeners.csv';
        $headers = CsvFileAttributes::getHeaders($fileName);

        $callback = function () use ($id) {
            CsvFileAttributes::makeContent('listeners', $id);
        };
        $response = [$callback, 200, $headers];
        return $response;
    }

    public static function sendFile(int $id)
    {
        $file = MakeListenerCsvFile::getFile($id);
        return response()->stream($file[0], $file[1], $file[2]);
    }
}
