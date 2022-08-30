<?php
declare (strict_types = 1);

namespace App\Services;

use App\Models\Conference;
use App\Services\MakeCsvFileInterface;

class MakeConferenceSvcFile implements MakeCsvFileInterface
{
    public static function getFile(int $id = 0)
    {
        $fileName = 'conferences.csv';
        $conferences = Conference::with('users', 'reports')->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );

        $columns = array('Title', 'Date', 'Address', 'Country', 'Number of reports', 'Number of listeners');

        $callback = function () use ($conferences, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($conferences as $conference) {

                $row['Title'] = $conference->title;
                $row['Date'] = $conference->date;
                $row['Address'] = MakeConferenceSvcFile::getAddress($conference->address_lat, $conference->address_lon);
                $row['Country'] = $conference->country;
                $row['Number of reports'] = count($conference->reports);
                $row['Number of listeners'] = count($conference->users);

                fputcsv($file, array($row['Title'], $row['Date'], $row['Address'], $row['Country'], $row['Number of reports'], $row['Number of listeners']));
            }

            fclose($file);
        };
        $response = [$callback, 200, $headers];
        return $response;
    }

    private static function getAddress($latitude, $longitude)
    {
        //google map api url
        $url = "https://maps.google.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=AIzaSyAWYpOvTuAYKad3lZf-c_RIvRz9wcEA1Ws";

        // send http request
        $geocode = file_get_contents($url);
        $json = json_decode($geocode);
        if (count($json->results) === 0) {
            return 'no where';
        }
        $address = $json->results[0]->formatted_address;

        return $address;
    }

    public static function sendFile(int $id = 0)
    {
        $file = MakeConferenceSvcFile::getFile();
        return response()->stream($file[0], $file[1], $file[2]);
    }
}
