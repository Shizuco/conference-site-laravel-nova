<?php
declare (strict_types = 1);

namespace App\Services;

use App\Services\CsvFileContent\CommentContent;
use App\Services\CsvFileContent\ConferenceContent;
use App\Services\CsvFileContent\ListenerContent;
use App\Services\CsvFileContent\ReportContent;

class CsvFileAttributes
{
    public static function getHeaders(string $filename)
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        ];
        return $headers;
    }

    public static function makeContent(string $type, int $id)
    {
        switch ($type) {
            case 'comment':
                CommentContent::get($id);
                break;
            case 'report':
                ReportContent::get($id);
                break;
            case 'conference':
                ConferenceContent::get();
                break;
            case 'listeners':
                ListenerContent::get($id);
                break;
        }

    }
}
