<?php
declare (strict_types = 1);

namespace App\Services;

use App\Models\Comment;
use App\Services\MakeCsvFileInterface;
use App\Services\CsvFileAttributes;

class MakeCommentSvcFile implements MakeCsvFileInterface
{
    public static function getFile (int $id){
        $fileName = 'comments.csv';
        $headers = CsvFileAttributes::getHeaders($fileName);

        $callback = function () use ($id){
            CsvFileAttributes::makeContent('comment', $id);
        };
        $response = [$callback, 200, $headers];
        return $response;
    }

    public static function sendFile(int $id)
    {
        $file = MakeCommentSvcFile::getFile($id);
        return response()->stream($file[0], $file[1], $file[2]);
    }
}