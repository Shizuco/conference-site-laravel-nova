<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateConferenceRequest;
use App\Http\Requests\UpdateConferenceRequest;
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
        return response()->json(Conference::where('title', $request->conf_title)->paginate(5));
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
        Conference::findOrFail($id)->delete();
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
}
