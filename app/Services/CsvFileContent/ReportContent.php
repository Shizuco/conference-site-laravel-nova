<?php
declare (strict_types = 1);

namespace App\Services\CsvFileContent;

use App\Models\Report;

class ReportContent
{
    public static function get(int $id)
    {
        $reports = Report::with('comments')->where('conference_id', $id)->get();
        $columns = array('Thema', 'Time', 'Description', 'Number of comments');
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
    }
}
