<?php

declare (strict_types = 1);

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

    public function index(Request $request){
        return Cache::get("meetings", $this->getAll($request));
    }

    public function show(int $id)
    {
        $meeting = $this->get($id);

        return response()->json($meeting);
    }

    public function store(array $request)
    {
        Cache::forget("meetings");
        $meeting = $this->create($request);
        return response()->json($meeting);
    }

    public function update(int $id, array $request)
    {
        $meet = $this->put($id, $request);
        return response()->json($meet);
    }

    public function destroy(int $id)
    {
        $this->delete($id);
    }
}