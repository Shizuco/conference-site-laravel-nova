<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateConferenceRequest;
use App\Http\Requests\UpdateConferenceRequest;
use App\Jobs\SendMailWithQueue;
use App\Models\Conference;
use App\Models\Report;
use Datetime;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Conference::Filters($request));
    }

    public function conferencesByName(Request $request)
    {
        return response()->json(Conference::where('title', 'LIKE', "%{$request->conf_title}%")->paginate(5));
    }

    public function show(int $id)
    {
        $time = $this->hasTime($id);
        $conf = Conference::findOrFail($id);
        $conf['hasTime'] = $time;
        return response()->json($conf);
    }

    public function store(CreateConferenceRequest $request)
    {
        $data = $request->validated();
        $data['category_id'] = $request->category_id;
        Conference::create($data);
    }

    public function update(UpdateConferenceRequest $request, int $id)
    {
        Conference::whereId($id)->update($request->validated());
    }

    public function destroy(int $id)
    {
        $this->sendMessage($id);
        Conference::findOrFail($id)->delete();
    }

    public function exportCsv(Request $request)
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
                $row['Address'] = $this->getAddress($conference->address_lat, $conference->address_lon);
                $row['Country'] = $conference->country;
                $row['Number of reports'] = count($conference->reports);
                $row['Number of listeners'] = count($conference->users);

                fputcsv($file, array($row['Title'], $row['Date'], $row['Address'], $row['Country'], $row['Number of reports'], $row['Number of listeners']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function hasTime(int $id)
    {
        $conference = Conference::findOrFail($id);
        $results = Report::orderBy('start_time')->where('conference_id', $id)->get();
        if (count($results) === 0) {
            return true;
        }
        $startTime = new Datetime($conference->date . 'T' . $conference->time . 'Z');
        $endTime = 0;
        $hasTime = 0;
        for ($a = 0; $a < count($results); $a++) {
            if ($a !== 0 && $a !== count($results) - 1) {
                $startTime = new Datetime($results[$a + 1]->start_time);
            }
            if ($a === count($results) - 1) {
                $endTime = new DateTime($conference->date . ' 23:59:59.000');
                $startTime = new Datetime($results[$a]->end_time);
            } else if ($a === 0) {
                $endTime = new Datetime($results[$a]->start_time);
            } else {
                $endTime = new Datetime($results[$a]->end_time);
            }
            $interval = $endTime->diff($startTime);
            $err = (($interval->format('%i') >= 10));
            if ($err) {
                $hasTime++;
            }
        }

        return ($hasTime !== 0) ? true : false;
    }

    private function sendMessage(int $id)
    {
        $users = Conference::with('users')->whereId($id)->get();
        $usersEmails = [];
        $message = '';
        foreach ($users as $user) {
            $message = 'Good afternoon, unfortunately the conference ' . $user->title . ' has been deleted by the administration.';
            foreach ($user->users as $userEmail) {
                array_push($usersEmails, $userEmail->email);
            }
        }
        foreach ($usersEmails as $email) {
            dispatch(new SendMailWithQueue($email, $message));
        }
    }

    private function getAddress($latitude, $longitude)
    {
        //google map api url
        $url = "https://maps.google.com/maps/api/geocode/json?latlng=$latitude,$longitude&key=AIzaSyAWYpOvTuAYKad3lZf-c_RIvRz9wcEA1Ws";
        
        // send http request
        $geocode = file_get_contents($url);
        $json = json_decode($geocode);
        if(count($json->results) === 0){
            return 'no where';
        }
        $address = $json->results[0]->formatted_address;
        
        return $address;
    }
}
