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

        $meetings = $this->getAll();

       /* return cache()->remember('meetings', Carbon::now()->addMinutes(5), function() {
            return $this->getAll();
        });*/
        return response()->json($meetings);
    }

    public function show($id)
    {
        $meeting = $this->get($id);

        return response()->json($meeting);
    }

    public static function store(array $request)
    {
        return response()->json(ZoomMeetingTrait::create($request));
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