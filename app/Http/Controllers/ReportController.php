<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportRequest;
use App\Models\Conference;
use App\Models\Report;
use App\Models\User;
use Auth;
use DateTime;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function index(int $id)
    {
        return response()->json(Report::All()->where('conference_id', $id));
    }

    public function show(int $conferenceId, int $reportId)
    {
        return Report::with('comments.users')->where('id', $reportId)->get();
    }

    public function isInRange(DateTime $dateToCheck, DateTime $startDate, DateTime $endDate)
    {
        return $dateToCheck >= $startDate && $dateToCheck <= $endDate;
    }

    public function isDateAvailable(Datetime $startTime, Datetime $endTime, int $id)
    {
        $reports = Report::All()->where('conference_id', $id);
        $isDateOk = 0;
        foreach ($reports as $report) {
            $startTimeExist = new Datetime($report->start_time);
            $endTimeExist = new Datetime($report->end_time);
            if ($this->isInRange($startTime, $startTimeExist, $endTimeExist) === true) {
                $isDateOk++;
                break;
            }
            if ($this->isInRange($endTime, $startTimeExist, $endTimeExist) === true) {
                $isDateOk++;
                break;
            }
            if ($startTime >= $endTime) {
                $isDateOk++;
                break;
            }
        }
        return ($isDateOk === 0) ? true : false;
    }

    public function isReportDurationLessThanHour(Datetime $startTimeExist, Datetime $endTimeExist)
    {
        $interval = ($startTimeExist->diff($endTimeExist));
        if (($interval->h > 1) || ($interval->h === 1 && $interval->i > 0)) {
            $error = ValidationException::withMessages([
                'start_time' => ['Maximum time of report should be less than hour'],
                'end_time' => ['Maximum time of report should be less than hour'],
            ]);
            throw $error;
        }
    }

    public function NearestTime(int $id)
    {
        $results = Report::orderBy('start_time')->where('conference_id', $id)->get();
        $start_time = new Datetime($conference->date . 'T' . $conference->time . 'Z');
        $end_time = 0;
        for ($a = 0; $a < count($results); $a++) {
            if ($a !== 0 && $a !== count($results) - 1) {
                $start_time = new Datetime($results[$a + 1]->start_time);
            }
            if ($a === count($results) - 1) {
                $end_time = new DateTime($conference->date . ' 23:59:59.000');
                $start_time = new Datetime($results[$a]->end_time);
            } else if ($a === 0) {
                $end_time = new Datetime($results[$a]->start_time);
            } else {
                $end_time = new Datetime($results[$a]->end_time);
            }
            $interval = $end_time->diff($start_time);
            $err = $interval->format('%i') >= 10;
            if ($err) {
                if ($a === 0) {
                    $error = ValidationException::withMessages([
                        'start_time' => ['Nearest time for start is ' . $start_time->format('Y-m-d H:i:s')],
                    ]);
                } else {
                    $error = ValidationException::withMessages([
                        'start_time' => ['Nearest time for start is ' . $results[$a]->end_time],
                    ]);
                }
                throw $error;
            }
        }
    }

    public function isDateInRangeOfConference(Datetime $startTimeExist, Datetime $endTimeExist, int $id)
    {
        $conference = Conference::Find($id)->first();
        $conStartTime = new DateTime($conference->date . 'T' . $conference->time . 'Z');
        $conEndTime = new DateTime($conference->date . 'T23:59:59.000000Z');
        if ($startTimeExist < $conStartTime) {
            $error = ValidationException::withMessages([
                'start_time' => ['Date must be in range of conference'],
            ]);
            throw $error;
        }

        if (($endTimeExist->diff($conEndTime)->format('%d') >= 1) || ($startTimeExist->diff($conStartTime)->format('%d') >= 1)) {
            $error = ValidationException::withMessages([
                'start_time' => ['Date must be in range of conference'],
                'end_time' => ['Date must be in range of conference'],
            ]);
            throw $error;
        }

    }

    public function store(CreateReportRequest $request, int $id)
    {
        $startTimeExist = new Datetime($request->start_time);
        $endTimeExist = new Datetime($request->end_time);
        $this->isReportDurationLessThanHour($startTimeExist, $endTimeExist);
        $this->isDateInRangeOfConference($startTimeExist, $endTimeExist, $id);
        $request->file('presentation')->storeAs('', $request->file('presentation')->getClientOriginalName());
        $data = $request->validated();
        if ($this->isDateAvailable($startTimeExist, $endTimeExist, $id) === true) {
            $data['conference_id'] = $id;
            $data['user_id'] = Auth::user()->id;
            $data['presentation'] = $request->file('presentation')->getClientOriginalName();
            Report::create($data);
        } else {
            $this->NearestTime($id);
        }
    }

    public function update(CreateReportRequest $request, int $conferenceId, int $reportId)
    {
        $rep = Report::findOrFail($reportId);
        $startTimeExist = new Datetime($request->start_time);
        $endTimeExist = new Datetime($request->end_time);
        $this->isReportDurationLessThanHour($startTimeExist, $endTimeExist);
        $this->isDateInRangeOfConference($startTimeExist, $endTimeExist, $conferenceId);
        $data = $request->validated();
        if ($rep->user_id === Auth::user()->id || $this->isDateAvailable($request->start_time, $request->end_time, $conferenceId) === true) {
            $data['conference_id'] = $conferenceId;
            $data['user_id'] = Auth::user()->id;
            if (gettype($request->file('presentation')) == 'object') {
                $request->file('presentation')->storeAs('', $request->file('presentation')->getClientOriginalName());
                $data['presentation'] = $request->file('presentation')->getClientOriginalName();
            } else {
                $data['presentation'] = $rep->presentation;
            }
            Report::whereId($reportId)->update($data);
        } else {
            $this->NearestTime($id);
        }
    }

    public function destroy(int $conferenceId)
    {
        $rep = Report::where('user_id', Auth::user()->id)->where('conference_id', $conferenceId)->get();
        $rep[0]->delete();
    }

    public function getFile(int $conferenceId, int $reportId)
    {
        $rep = Report::findOrFail($reportId);
        //$headers = array('Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation','Content-Disposition' =>  'attachment; filename="' . $rep->presentation . '"');

        //return response()->download(storage_path() . "/app/" . $rep->presentation, $rep->presentation, $headers);
        //return new Response(storage_path() . "/app/" . $rep->presentation, 200, array(
           // 'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
           // 'Content-Disposition' =>  'attachment; filename="' . $rep->presentation . '"'
       // ));
        return response()->download(storage_path() . "/app/" . $rep->presentation);
    }
}