<?php
declare (strict_types = 1);

namespace App\Services\CsvFileContent;

use App\Models\Conference;

class ConferenceContent
{
    public function get()
    {
        $conferences = Conference::with('users', 'reports')->get();
        $columns = array('Title', 'Date', 'Address', 'Country', 'Number of reports', 'Number of listeners');
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($conferences as $conference) {

            $row['Title'] = $conference->title;
            $row['Date'] = $conference->date;
            $row['Address'] = $this->getAddress($conference->latitude, $conference->longitude);
            $row['Country'] = $conference->country;
            $row['Number of reports'] = count($conference->reports);
            $row['Number of listeners'] = count($conference->users);

            fputcsv($file, array($row['Title'], $row['Date'], $row['Address'], $row['Country'], $row['Number of reports'], $row['Number of listeners']));
        }

        fclose($file);
    }

    private function getAddress($latitude, $longitude)
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
}
