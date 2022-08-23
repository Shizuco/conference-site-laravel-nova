<?php

namespace App\Jobs;


use App\Events\DownloadExportCsvFile;
use App\Services\MakeConferenceSvcFile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SvcFile implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public function __construct()
    {
       //
    }

    public function handle()
    {
        MakeConferenceSvcFile::getFile();
    }
}
