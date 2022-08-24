<?php

namespace App\Jobs;


use App\Events\DownloadExportCsvFile;
use App\Services\MakeConferenceSvcFile;
use App\Services\MakeReportSvcFile;
use App\Services\MakeCommentSvcFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
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
        switch($this->method){
            case 'conference':
                MakeConferenceSvcFile::getFile();
                break;
            case 'report':
                MakeReportSvcFile::getFile($this->id);
                break;
            case 'comment':
                MakeCommentSvcFile::getFile($this->id);
                break;
        }
    }
}
