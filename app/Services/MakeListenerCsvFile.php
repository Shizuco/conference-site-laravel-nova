<?php
declare (strict_types = 1);

namespace App\Services;

use App\Models\User;
use App\Services\MakeCsvFileInterface;
use App\Services\CsvFileAttributes;

class MakeListenerCsvFile implements MakeCsvFileInterface
{
    protected $attr;
    protected $csvFile;

    public function __construct(CsvFileAttributes $attr, MakeListenerCsvFile $csvFile)
    {
        $this->attr = $attr;
        $this->csvFile = $csvFile;
    }

    public function getFile(int $id)
    {
        $fileName = 'listeners.csv';
        $headers = $this->attr->getHeaders($fileName);

        $callback = function () use ($id) {
            $this->attr->makeContent('listeners', $id);
        };
        $response = [$callback, 200, $headers];
        return $response;
    }

    public function sendFile(int $id)
    {
        $file = $this->csvFile->getFile($id);
        return response()->stream($file[0], $file[1], $file[2]);
    }
}
