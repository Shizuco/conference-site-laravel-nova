<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportRequest;
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

   public function isDateAvailable(CreateReportRequest $request, int $id){
        $reports = Report::All()->where('conference_id', $id);
        $isDateOk = 0;
        $startTime = new Datetime($request->start_time);
        $endTime = new Datetime($request->end_time);
        for($a = 0; $a < count($reports); $a++){
            $startTimeExist = new Datetime($reports[$a]->start_time);
            $endTimeExist = new Datetime($reports[$a]->end_time);
            if($this->isInRange($startTime, $startTimeExist, $endTimeExist) === true){
                $isDateOk++;
                break;
            }
            if($this->isInRange($endTime, $startTimeExist, $endTimeExist) === true){
                $isDateOk++;
                break;
            }
        }
        if($isDateOk === 0){
            return true;
        }
        else{
            return false;
        }
    }

    public function store(CreateReportRequest $request, int $id)
    {
        $data = $request->validated();
        if($this->isDateAvailable($request, $id) === true){
            $data['conference_id'] = $id;
            $data['user_id'] = Auth::user()->id;
            Report::create($data);  
        }
        
    }

    public function update(CreateReportRequest $request, int $conference_id, int $report_id)
    {
        $rep = Report::findOrFail($report_id);
        if ($rep->user_id === Auth::user()->id) {
            $data = $request->validated();
            $data['conference_id'] = $conference_id;
            $data['user_id'] = Auth::user()->id;
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

}
