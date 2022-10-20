<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Conference;
use App\Models\Report;
use App\Services\ExportCsvFile;
use App\Services\MakeConferenceCsvFile;
use Datetime;
use Auth;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    protected $exportCsv;
    protected $makeCsv;

    public function __construct(ExportCsvFile $exportCsv, MakeConferenceCsvFile $makeCsv)
    {
        $this->exportCsv = $exportCsv;
        $this->makeCsv = $makeCsv;
    }

    public function index(Request $request)
    {
        return response()->json(Conference::Filters($request));
    }

    public function conferencesByName(Request $request)
    {
        if (Auth::user()->role === 'admin') {
            abort(403, 'Access denide');
        }
        return response()->json(Conference::where('title', 'LIKE', "%{$request->conf_title}%")->paginate(5));
    }

    public function show(int $id)
    {
        $time = $this->hasTime($id);
        $conf = Conference::findOrFail($id);
        $conf['hasTime'] = $time;
        return response()->json($conf);
    }

    public function exportCsv(Request $request)
    {
        $this->exportCsv->export('conference', 0);
    }

    public function downloadCsv()
    {
        return $this->makeCsv->sendFile();
    }

    private function hasTime(int $id)
    {
        $conference = Conference::where('id', $id)->firstOrFail();
        $results = Report::orderBy('start_time')->where('conference_id', $id)->get();
        if (count($results) === 0) {
            return true;
        }
        $startTime = $conference->date->format('Y-m-d') . ' ' . $conference->time;
        $startTime = new Datetime($startTime);
        $endTime = 0;
        $hasTime = 0;
        for ($a = 0; $a < count($results); $a++) {
            if ($a !== 0 && $a !== count($results) - 1) {
                $startTime = new Datetime($results[$a + 1]->start_time->format('Y-m-d H:i:s'));
            }
            if ($a === count($results) - 1) {
                $endTime = new DateTime($conference->date->format('Y-m-d') . ' 23:59:59.000');
                $startTime = new Datetime($results[$a]->end_time->format('Y-m-d H:i:s'));
            } else if ($a === 0) {
                $endTime = new Datetime($results[$a]->start_time->format('Y-m-d H:i:s'));
            } else {
                $endTime = new Datetime($results[$a]->end_time->format('Y-m-d H:i:s'));
            }
            $interval = $endTime->diff($startTime);
            $err = (($interval->format('%i') >= 10) || $interval->format('%h') > 0);
            if ($err) {
                $hasTime++;
            }
        }

        return ($hasTime !== 0) ? true : false;
    }

    private function sendMessage(int $id)
    {
        $this->message->sendMessage(0, $id, 0);
    }
}
