<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateReportRequest;
use App\Models\Report;
use App\Models\User;
use Auth;

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

    public function store(CreateReportRequest $request, int $id)
    {
        $data = $request->validated();
        $data['conference_id'] = $id;
        $data['user_id'] = Auth::user()->id;
        Report::create($data);
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
        $rep = Report::findOrFail($report_id);
        if ($rep->user_id === Auth::user()->id) {
            Report::findOrFail($id)->delete();
        }
    }

}
