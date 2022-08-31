<?php
declare (strict_types = 1);

namespace App\Services;

use App\Services\CsvFileAttributes;
use App\Services\MakeCsvFileInterface;

class MakeCommentCsvFile implements MakeCsvFileInterface
{
    protected $attr;
    protected $csvFile;

    public function __construct(CsvFileAttributes $attr, MakeCommentCsvFile $csvFile)
    {
        $this->attr = $attr;
        $this->csvFile = $csvFile;
    }

    public function getFile(int $id)
    {
        $fileName = 'comments.csv';
        $headers = $this->attr->getHeaders($fileName);

        $callback = function () use ($id) {
            $this->attr->makeContent('comment', $id);
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
