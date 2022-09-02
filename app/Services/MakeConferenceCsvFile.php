<?php
declare (strict_types = 1);

namespace App\Services;

use App\Models\Conference;
use App\Services\MakeCsvFileInterface;
use App\Services\CsvFileAttributes;

class MakeConferenceCsvFile implements MakeCsvFileInterface
{

    protected $attr;

    public function __construct(CsvFileAttributes $attr){
        $this->attr = $attr;
    }

    public function getFile(int $id = 0)
    {
        $fileName = 'conferences.csv';
        $headers = $this->attr->getHeaders($fileName);

        $callback = function () {
            $this->attr->makeContent('conference', 0);
        };
        $response = [$callback, 200, $headers];
        return $response;
    }

    public function sendFile(int $id = 0)
    {
        $file = $this->getFile();
        return response()->stream($file[0], $file[1], $file[2]);
    }
}
