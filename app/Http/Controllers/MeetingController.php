<?php

namespace App\Http\Controllers;

use App\Models\ZoomMeeting;
use App\Traits\ZoomMeetingTrait;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class MeetingController extends Controller
{
    use ZoomMeetingTrait;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function index(){
        return Cache::get("meetings", $this->getAll());
    }

    public function show($id)
    {
        $meeting = $this->get($id);

        return response()->json($meeting);
    }

    public function store(array $request)
    {
        Cache::forget("meetings");
        $meeting = $this->create($request);
        Cache::put('meetings', $this->getAll()['data']
        );
        return response()->json($meeting);
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