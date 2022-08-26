<?php

namespace App\Http\Controllers;

use App\Models\ZoomMeeting;
use App\Traits\ZoomMeetingTrait;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    use ZoomMeetingTrait;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function index(){
        $meetings = $this->get();

        return response()->json($meetings);
    }

    public function show($id)
    {
        $meeting = $this->get($id);

        return response()->json($meeting);
    }

    public function store(Request $request)
    {
        return response()->json($this->create($request->all()));
    }

    public function update($meeting, Request $request)
    {
        $this->update($meeting->zoom_meeting_id, $request->all());
    }

    public function destroy(ZoomMeeting $meeting)
    {
        $this->delete($meeting->id);
    }
}