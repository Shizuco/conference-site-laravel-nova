<?php

namespace App\Services;

use App\Models\Report;

class MakeReportSvcFile
{
    public static function getFile (int $id){
        $fileName = 'reports.csv';
        $reports = Report::with('comments')->where('conference_id', $id)->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );

        $columns = array('Thema', 'Time', 'Description', 'Number of comments');

        $callback = function () use ($reports, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($reports as $report) {

                $row['Thema'] = $report->thema;
                $row['Date'] = $report->start_time . ' to ' . $report->end_time;
                $row['Description'] = $report->description;
                $row['Number of comments'] = count($report->comments);

                fputcsv($file, array($row['Thema'], $row['Date'], $row['Description'], $row['Number of comments']));
            }

            fclose($file);
        };
        $response = [$callback, 200, $headers];
        return $response;
    }

    public static function sendFile(int $id)
    {
        $file = MakeReportSvcFile::getFile($id);
        return response()->stream($file[0], $file[1], $file[2]);
    }
}