<?php
declare (strict_types = 1);

namespace App\Services\CsvFileContent;

use App\Models\User;

class ListenerContent
{
    public function get(int $id)
    {
        $listeners = User::with('conferences')->where('role', 'listener')->get();
        $columns = array('Name', 'Surname', 'Birthday', 'Country', 'Phone', 'Email');
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
    }
}
