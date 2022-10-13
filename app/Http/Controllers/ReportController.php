<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Controllers\MeetingController;
use App\Http\Requests\CreateReportRequest;
use App\Models\Conference;
use App\Models\Report;
use App\Models\User;
use App\Services\ExportCsvFile;
use App\Services\MakeReportCsvFile;
use App\Services\Messages\SendMessageAboutChangeReportTime;
use App\Services\Messages\SendMessageAboutNewParticipant;
use App\Services\Messages\SendMessageAboutReportDeletedByAdmin;
use Auth;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{

    protected $exportCsv;
    protected $reportCsv;
    protected $participantMessage;
    protected $reportDeleteMessage;
    protected $reportTimeMessage;
    protected $meeting;

    public function __construct(ExportCsvFile $exportCsv, MakeReportCsvFile $reportCsv, SendMessageAboutNewParticipant $participantMessage, SendMessageAboutReportDeletedByAdmin $reportDeleteMessage, SendMessageAboutChangeReportTime $reportTimeMessage, MeetingController $meeting)
    {
        $this->exportCsv = $exportCsv;
        $this->reportCsv = $reportCsv;
        $this->participantMessage = $participantMessage;
        $this->reportDeleteMessage = $reportDeleteMessage;
        $this->reportTimeMessage = $reportTimeMessage;
        $this->meeting = $meeting;
    }

    public function index(Request $request, int $id)
    {
        return response()->json(Report::Filters($request, $id));
    }

    public function reportsByName(Request $request)
    {
        return response()->json(Report::where('thema', 'LIKE', "%{$request->rep_title}%")->paginate(5));
    }

    public function show(int $conferenceId, int $reportId)
    {
        return Report::with('comments.users')->where('id', $reportId)->get();
    }

    public function store(CreateReportRequest $request, int $id)
    {
        $conference = Conference::Find($id)->first();
        $startTimeExist = new Datetime($request->start_time);
        $startTimeExist->setTimezone(new DateTimeZone('GMT'));
        $startTimeExist->add(new DateInterval('PT3H'));
        $endTimeExist = new Datetime($request->end_time);
        $endTimeExist->setTimezone(new DateTimeZone('GMT'));
        $endTimeExist->add(new DateInterval('PT3H'));
        $duration = ($endTimeExist->getTimestamp() - $startTimeExist->getTimestamp());
        $this->isReportDurationLessThanHour($startTimeExist, $endTimeExist);
        $this->isDateInRangeOfConference($startTimeExist, $endTimeExist, $id);
        $request->file('presentation')->storeAs('', $request->file('presentation')->getClientOriginalName());
        $data = $request->validated();
        if ($this->isDateAvailable($startTimeExist, $endTimeExist, $id) === true) {
            $data['start_time'] = $startTimeExist->format('Y-m-d H:i:s');
            $data['end_time'] = $endTimeExist->format('Y-m-d H:i:s');
            $data['conference_id'] = $id;
            $data['category_id'] = $request->category_id;
            $data['user_id'] = Auth::user()->id;
            $data['duration'] = $duration;
            $data['presentation'] = $request->file('presentation')->getClientOriginalName();
            if ($request->isOnline === "true") {
                $zoom_id = $this->meeting->store([
                    "topic" => $request->thema,
                    "duration" => $duration / 60,
                    "start_time" => $startTimeExist,
                    "host_video" => "true",
                    "participant_video" => "true",
                ]);
                $data['zoom_meeting_id'] = $zoom_id->original['data']['id'];
            }
            Report::create($data);
            $this->sendMessage($request, $id, 0, 'new participant');
        } else {
            $this->NearestTime($id);
        }
    }

    public function update(CreateReportRequest $request, int $conferenceId, int $reportId)
    {
        $rep = Report::where('id', $reportId)->firstOrFail();
        $startTimeExist = new Datetime($request->start_time);
        $startTimeExist->setTimezone(new DateTimeZone('GMT'));
        $startTimeExist->add(new DateInterval('PT3H'));
        $endTimeExist = new Datetime($request->end_time);
        $endTimeExist->setTimezone(new DateTimeZone('GMT'));
        $endTimeExist->add(new DateInterval('PT3H'));
        $this->isReportDurationLessThanHour($startTimeExist, $endTimeExist);
        $this->isDateInRangeOfConference($startTimeExist, $endTimeExist, $conferenceId);
        $data = $request->validated();
        $duration = ($endTimeExist->getTimestamp() - $startTimeExist->getTimestamp());
        if ($rep->user_id === Auth::user()->id || $this->isDateAvailable($request->start_time, $request->end_time, $conferenceId) === true) {
            $data['conference_id'] = $conferenceId;
            $data['user_id'] = Auth::user()->id;
            if (gettype($request->file('presentation')) == 'object') {
                $request->file('presentation')->storeAs('', $request->file('presentation')->getClientOriginalName());
                $data['presentation'] = $request->file('presentation')->getClientOriginalName();
            } else {
                $data['presentation'] = $rep->presentation;
            }
            $data['duration'] = $duration;
            if ($request->start_time !== $rep->start_time) {
                $this->sendMessage($request, $conferenceId, $rep, 'change report time');
            }
            Report::where('id', $reportId)->update($data);
        } else {
            $this->nearestTime($id);
        }
    }

    public function destroy(int $conferenceId, int $report_id)
    {
        if ($report_id == 0) {
            $rep = Report::where('conference_id', $conferenceId)->where('user_id', Auth::user()->id)->first();
            if ($rep->zoom_meeting_id !== null) {
                $this->meeting->destroy($rep->zoom_meeting_id);
            }
            $rep->delete();
        } else {
            $rep = Report::where('id', $report_id)->first();
            if ($rep->zoom_meeting_id !== null) {
                $this->meeting->destroy($rep->zoom_meeting_id);
            }
            $this->sendMessage(0, $conferenceId, $rep, 'deleted by admin');
            $rep->delete();
        }

    }

    public function getFile(int $conferenceId, int $reportId)
    {
        $rep = Report::findOrFail($reportId);
        return response()->download(storage_path() . "/app/" . $rep->presentation);
    }

    public function exportCsv(Request $request, int $id)
    {
        $this->exportCsv->export('report', $id);
    }

    public function downloadCsv(int $id)
    {
        return $this->reportCsv->sendFile($id);
    }

    private function sendMessage($request, int $id, $report, string $whichMessage)
    {
        switch ($whichMessage) {
            case ('new participant'):
                $this->participantMessage->sendMessage($request, $id, $report);
                break;
            case ('deleted by admin'):
                $this->reportDeleteMessage->sendMessage($request, $id, $report);
                break;
            case ('change report time'):
                $this->reportTimeMessage->sendMessage($request, $id, $report);
                break;
        }

    }

    private function isInRange(DateTime $dateToCheck, DateTime $startDate, DateTime $endDate)
    {
        return $dateToCheck >= $startDate && $dateToCheck <= $endDate;
    }

    private function isDateAvailable(Datetime $startTime, Datetime $endTime, int $id)
    {
        $reports = Report::where('conference_id', $id)->get();
        $isDateOk = 0;
        foreach ($reports as $report) {
            $startTimeExist = new Datetime($report->start_time->format('Y-m-d H:i:s'));
            $startTimeExist->setTimezone(new DateTimeZone('GMT'));
            $endTimeExist = new Datetime($report->end_time->format('Y-m-d H:i:s'));
            $endTimeExist->setTimezone(new DateTimeZone('GMT'));
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

    private function isReportDurationLessThanHour(Datetime $startTimeExist, Datetime $endTimeExist)
    {
        $interval = ($startTimeExist->diff($endTimeExist));
        if ($interval->format('%h') >= 1) {
            $error = ValidationException::withMessages([
                'start_time' => ['Maximum time of report should be less than hour'],
                'end_time' => ['Maximum time of report should be less than hour'],
            ]);
            throw $error;
        }
    }

    private function nearestTime(int $id)
    {
        $conference = Conference::wwhere('id', $id)->firstOrFail();
        $results = Report::orderBy('start_time')->where('conference_id', $id)->get();
        $startTime = new DateTime($conference->date->format('Y-m-d') . ' ' . $conference->time);
        $endTime = 0;
        for ($a = 0; $a < count($results); $a++) {
            if ($a !== 0 && $a !== count($results) - 1) {
                $startTime = new Datetime($results[$a + 1]->start_time->format('Y-m-d H:i:s'));
            }
            if ($a === count($results) - 1) {
                $endTime = new DateTime($conference->date . ' 23:59:59.000');
                $startTime = new Datetime($results[$a]->end_time->format('Y-m-d H:i:s'));
            } else if ($a === 0) {
                $endTime = new Datetime($results[$a]->start_time->format('Y-m-d H:i:s'));
            } else {
                $endTime = new Datetime($results[$a]->end_time->format('Y-m-d H:i:s'));
            }
            $interval = $endTime->diff($startTime);
            $err = $interval->format('%i') >= 10;
            if ($err) {
                if ($a === 0) {
                    $error = ValidationException::withMessages([
                        'start_time' => ['Nearest time for start is ' . $startTime->format('Y-m-d H:i:s')],
                    ]);
                } else {
                    $error = ValidationException::withMessages([
                        'start_time' => ['Nearest time for start is ' . $results[$a]->end_time->format('Y-m-d H:i:s')],
                    ]);
                }
                throw $error;
            }
        }
    }

    private function isDateInRangeOfConference(Datetime $startTimeExist, Datetime $endTimeExist, int $id)
    {
        $conference = Conference::where('id', $id)->firstOrFail();
        $conStartTime = new DateTime($conference->date->format('Y-m-d') . ' ' . $conference->time);
        $conStartTime->setTimezone(new DateTimeZone('GMT'));
        $conEndTime = new DateTime($conference->date->format('Y-m-d') . ' 23:59:59.000000');
        $conEndTime->setTimezone(new DateTimeZone('GMT'));
        if ($startTimeExist < $conStartTime) {
            $error = ValidationException::withMessages([
                'start_time' => ['Date must be in range of conference'],
            ]);
            throw $error;
        }
        if (($endTimeExist->diff($conEndTime)->d >= 1) || ($startTimeExist->diff($conStartTime)->d >= 1)) {
            $error = ValidationException::withMessages([
                'start_time' => ['Date must be in range of conference'],
                'end_time' => ['Date must be in range of conference'],
            ]);
            throw $error;
        }

    }
}
