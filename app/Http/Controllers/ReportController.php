<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Report;
use App\Models\User;
use Auth, DateTime;

class ReportController extends Controller
{
    public function index(int $id)
    {
        return response()->json(Report::All()->where('conference_id', $id));
    }

    public function show(int $conference_id, int $report_id)
    {
        return response()->json(Report::All()->where('conference_id', $conference_id)->where('id', $report_id));
    }

    public function isInRange(DateTime $dateToCheck, DateTime $startDate, DateTime $endDate)
    {
        return $dateToCheck >= $startDate && $dateToCheck <= $endDate;
    }

    public function isDateAvailable($start_time, $end_time, int $id)
    {
        $reports = Report::All()->where('conference_id', $id);
        $isDateOk = 0;
        $startTime = new Datetime($start_time);
        $endTime = new Datetime($end_time);
        foreach($reports as $report){
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
        }
        return ($isDateOk === 0) ? true : false;
    }

    public function store(CreateReportRequest $request, int $id)
    {  
        //return $request->file('presentation')->getName();
        $request->file('presentation')-> storeAs('',$request->file('presentation')->getClientOriginalName());
        $data = $request->validated();
        if ($this->isDateAvailable($request->start_time, $request->end_time, $id) === true) { 
            $data['conference_id'] = $id;
            $data['user_id'] = Auth::user()->id;
            $data['presentation'] = $request->file('presentation')->getClientOriginalName();
            Report::create($data);
        }
    }

    public function update(CreateReportRequest $request, int $conference_id, int $report_id)
    {
        $rep = Report::findOrFail($report_id);
        $request->file('presentation')->store('public/presentations/');
        $data = $request->validated();
        if ($rep->user_id === Auth::user()->id) {
            $data['conference_id'] = $conference_id;
            $data['user_id'] = Auth::user()->id;
            $data['presentation'] = $request->file('presentation')->getClientOriginalName();

            Report::whereId($report_id)->update($data);
        }
    }

    public function destroy(int $id)
    {
        $rep = Report::findOrFail($id);
        if ($rep->user_id === Auth::user()->id) {
            Report::findOrFail($id)->delete();
        }
    }

    public function getFile(int $conference_id, int $report_id)
    {
        $rep = Report::findOrFail($report_id);
        if (Storage::exists('presentatins/', $rep->presentation)) {
           return response()->json($rep);
        }
        else{
            return response()->json(Storage::get($rep->presentation));
        }
    }
}
