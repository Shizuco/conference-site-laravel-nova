<?php
declare (strict_types = 1);

namespace App\Jobs;

use App\Services\MakeCommentSvcFile;
use App\Services\MakeConferenceSvcFile;
use App\Services\MakeListenerSvcFile;
use App\Services\MakeReportSvcFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SvcFile implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $method;
    public $id;

    public function __construct($method, $id)
    {
        $this->method = $method;
        $this->$id = $id;
    }

    public function handle()
    {
        switch ($this->method) {
            case 'conference':
                MakeConferenceSvcFile::getFile();
                break;
            case 'report':
                MakeReportSvcFile::getFile($this->id);
                break;
            case 'comment':
                MakeCommentSvcFile::getFile($this->id);
                break;
            case 'listeners':
                MakeListenerSvcFile::getFile($this->id);
                break;
        }
    }
}
