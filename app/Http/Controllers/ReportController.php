<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\ValidationException;
use App\Models\Report;
use App\Models\Conference;
use App\Models\User;
use App\Models\Comment;
use Auth, DateTime, Exception, Response;

class ReportController extends Controller
{
    public function index(int $id)
    {
        return response()->json(Report::All()->where('conference_id', $id));
    }

    public function show(int $conference_id, int $report_id)
    {
        return Report::with('comments.users')->where('id', $report_id)->get();
    }

    public function isInRange(DateTime $dateToCheck, DateTime $startDate, DateTime $endDate)
    {
        return $dateToCheck >= $startDate && $dateToCheck <= $endDate;
    }

    public function isDateAvailable(Datetime $start_time, Datetime $end_time, int $id)
    {
        $reports = Report::All()->where('conference_id', $id);
        $isDateOk = 0;
        foreach($reports as $report){
            $startTimeExist = new Datetime($report->start_time);
            $endTimeExist = new Datetime($report->end_time);
            if ($this->isInRange($start_time, $startTimeExist, $endTimeExist) === true) {
                $isDateOk++;
                break;
            }
            if ($this->isInRange($end_time, $startTimeExist, $endTimeExist) === true) {
                $isDateOk++;
                break;
            }
            if($start_time >= $end_time){
                $isDateOk++;
                break;
            }
        }
        return ($isDateOk === 0) ? true : false;
    }

    public function isReportDurationLessThanHour(Datetime $startTimeExist, Datetime $endTimeExist){
        $interval = ($startTimeExist->diff($endTimeExist));
        if($interval->format('%h') > 1){
            $error = ValidationException::withMessages([
                'start_time' => ['Maximum time of report should be less than hour'],
                'end_time' => ['Maximum time of report should be less than hour']
             ]);
            throw $error;
        }
    }

    public function NearestTime(int $id){
        $results = Report::orderBy('start_time')->where('conference_id', $id)->get();
            $start_time = new Datetime($conference->date . 'T' . $conference->time . 'Z');
            $end_time = 0;
            for($a = 0; $a < count($results); $a++){
                if( $a !== 0 && $a !== count($results) - 1){
                    $start_time = new Datetime($results[$a+1]->start_time);
                }
                if($a === count($results) - 1){
                    $end_time = new DateTime($conference->date . ' 23:59:59.000');
                    $start_time = new Datetime($results[$a]->end_time);
                }
                else if($a===0){
                    $end_time = new Datetime($results[$a]->start_time);
                }
                else{
                    $end_time = new Datetime($results[$a]->end_time); 
                }
                $interval = $end_time->diff($start_time);
                $err = $interval->format('%i')>=10;
                if($err){
                    if($a === 0){
                        $error = ValidationException::withMessages([
                            'start_time' => ['Nearest time for start is ' . $start_time->format('Y-m-d H:i:s')]
                        ]);
                    }
                    else{
                        $error = ValidationException::withMessages([
                            'start_time' => ['Nearest time for start is ' . $results[$a]->end_time]
                        ]);
                    }  
                    throw $error;
                }
            }   
    }

    public function isDateInRangeOfConference(Datetime $startTimeExist, Datetime $endTimeExist, int $id){
        $conference = Conference::Find($id)->first();
        $conStartTime = new DateTime($conference->date . 'T' . $conference->time . 'Z');
        $conEndTime = new DateTime($conference->date . 'T23:59:59.000000Z');
        if($startTimeExist<$conStartTime){
            $error = ValidationException::withMessages([
                'start_time' => ['Date must be in range of conference']
            ]);
            throw $error;
        }

        if(($endTimeExist->diff($conEndTime)->format('%d') >= 1) || ($startTimeExist->diff($conStartTime)->format('%d') >= 1)){
            $error = ValidationException::withMessages([
                'start_time' => ['Date must be in range of conference'],
                'end_time' => ['Date must be in range of conference']
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
        $request->file('presentation')-> storeAs('',$request->file('presentation')->getClientOriginalName());
        $data = $request->validated();
        if ($this->isDateAvailable($startTimeExist, $endTimeExist, $id) === true) { 
            $data['conference_id'] = $id;
            $data['user_id'] = Auth::user()->id;
            $data['presentation'] = $request->file('presentation')->getClientOriginalName();
            Report::create($data);
        }
        else{
            $this->NearestTime($id);
        }
    }

    public function update(CreateReportRequest $request, int $conference_id, int $report_id)
    {
        $rep = Report::findOrFail($report_id);
        $startTimeExist = new Datetime($request->start_time);
        $endTimeExist = new Datetime($request->end_time);
        $this->isReportDurationLessThanHour($startTimeExist, $endTimeExist);
        $this->isDateInRangeOfConference($startTimeExist, $endTimeExist, $conference_id);
        $data = $request->validated();
        if ($rep->user_id === Auth::user()->id || $this->isDateAvailable($request->start_time, $request->end_time, $conference_id) === true) {
            $data['conference_id'] = $conference_id;
            $data['user_id'] = Auth::user()->id;
            if(gettype($request->file('presentation')) == 'object'){
                $request->file('presentation')-> storeAs('',$request->file('presentation')->getClientOriginalName());   
                $data['presentation'] = $request->file('presentation')->getClientOriginalName();  
            }
            else{
                $data['presentation'] = $rep->presentation;
            }
            Report::whereId($report_id)->update($data);
        }
        else{
            $this->NearestTime($id);
        }
    }

    public function destroy(int $conference_id)
    {
        $rep = Report::where('user_id', Auth::user()->id)->where('conference_id', $conference_id)->get();
        $rep[0]->delete();
    }

    public function getFile(int $conference_id, int $report_id)
    {
        $rep = Report::findOrFail($report_id);
        return response()->download(storage_path() . "/app/" . $rep->presentation);
    }
}
