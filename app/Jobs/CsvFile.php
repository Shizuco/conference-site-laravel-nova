<?php
declare (strict_types = 1);

namespace App\Jobs;

use App\Services\MakeCommentCsvFile;
use App\Services\MakeConferenceCsvFile;
use App\Services\MakeListenerCsvFile;
use App\Services\MakeReportCsvFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CsvFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $method;
    public int $id;

    public function __construct(string $method, int $id)
    {
        $this->method = $method;
        $this->$id = $id;
    }

    public function handle()
    {
        switch ($this->method) {
            case 'conference':
                MakeConferenceCsvFile::getFile();
                break;
            case 'report':
                MakeReportCsvFile::getFile($this->id);
                break;
            case 'comment':
                MakeCommentCsvFile::getFile($this->id);
                break;
            case 'listeners':
                MakeListenerCsvFile::getFile($this->id);
                break;
        }
    }
}
