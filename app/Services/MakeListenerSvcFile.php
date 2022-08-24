<?php

namespace App\Services;

use App\Models\User;

class MakeListenerSvcFile
{

    public static function getFile(int $id)
    {
        $fileName = 'listeners.csv';
        $listeners = User::with('conferences')->where('role', 'listener')->get();
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0",
        );

        $columns = array('Name', 'Surname', 'Birthday', 'Country', 'Phone', 'Email');

        $callback = function () use ($listeners, $columns, $id) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($listeners as $listener) {
                foreach ($listener->conferences as $conference) {
                    if ($conference['pivot']['conference_id'] == $id && $conference['pivot']['user_id'] == $listener->id) {
                        $row['Name'] = $listener->name;
                        $row['Surname'] = $listener->surname;
                        $row['Birthday'] = $listener->birthday;
                        $row['Country'] = $listener->country;
                        $row['Phone'] = $listener->phone;
                        $row['Email'] = $listener->email;

                        fputcsv($file, array($row['Name'], $row['Surname'], $row['Birthday'], $row['Country'], $row['Phone'], $row['Email']));

                    }
                }
            }
            fclose($file);
        };
        $response = [$callback, 200, $headers];
        return $response;
    }

    public static function sendFile(int $id)
    {
        $file = MakeListenerSvcFile::getFile();
        return response()->stream($file[0], $file[1], $file[2]);
    }
}
