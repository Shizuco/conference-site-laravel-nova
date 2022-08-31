<?php
declare (strict_types = 1);

namespace App\Services;

use App\Services\CsvFileContent\CommentContent;
use App\Services\CsvFileContent\ConferenceContent;
use App\Services\CsvFileContent\ListenerContent;
use App\Services\CsvFileContent\ReportContent;

class CsvFileAttributes
{

    protected $comment;
    protected $report;
    protected $conference;
    protected $listener;

    public function __constuct(CommentContent $comment, ReportContent $report, ConferenceContent $conference, ListenerContent $listener)
    {
        $this->comment = $comment;
        $this->report = $report;
        $this->conference = $conference;
        $this->listener = $listener;
    }

    public function getHeaders(string $filename)
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

    public function makeContent(string $type, int $id)
    {
        switch ($type) {
            case 'comment':
                $this->comment->get($id);
                break;
            case 'report':
                $this->report->get($id);
                break;
            case 'conference':
                $this->conference->get();
                break;
            case 'listeners':
                $this->listener->get($id);
                break;
        }

    }
}
