<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Services\MakeReportSvcFile;
use App\Jobs\SvcFile;
use App\Events\DownloadExportCsvFile;
use App\Http\Requests\CreateReportRequest;
use App\Jobs\SendMailWithQueue;
use App\Models\Conference;
use App\Models\Report;
use App\Models\User;
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
            Report::create($data);
            $this->sendMessage($request, $id, 0, 'new participant');
        } else {
            $this->NearestTime($id);
        }
    }

    public function update(CreateReportRequest $request, int $conferenceId, int $reportId)
    {
        $rep = Report::findOrFail($reportId);
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
            Report::whereId($reportId)->update($data);
        } else {
            $this->NearestTime($id);
        }
    }

    public function destroy(int $conferenceId, int $report_id)
    {
        $rep = Report::whereId($report_id)->first();
        $this->sendMessage(0, $conferenceId, $rep, 'deleted by admin');
        $rep->delete();
    }

    public function getFile(int $conferenceId, int $reportId)
    {
        $rep = Report::findOrFail($reportId);
        return response()->download(storage_path() . "/app/" . $rep->presentation);
    }

    public function exportCsv(Request $request, int $id)
    {
        event(new DownloadExportCsvFile('start'));
        sleep(5);
        dispatch(new SvcFile('report', $id));
        event(new DownloadExportCsvFile('done'));
    }

    public function downloadCsv(int $id)
    {
        return MakeReportSvcFile::sendFile($id);
    }

    private function sendMessage($request, int $id, $report, string $whichMessage)
    {
        switch ($whichMessage) {
            case ('new participant'):
                if (Auth::user()->role === 'announcer') {
                    $startTimeExist = new Datetime($request->start_time);
                    $startTimeExist->setTimezone(new DateTimeZone('GMT'));
                    $startTimeExist->add(new DateInterval('PT3H'));
                    $startTimeExist = $startTimeExist->format('Y-m-d H:i:s');
                    $users = Conference::with('users')->whereId($id)->get();
                    $report = Report::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
                    $usersEmails = [];
                    $message = '';
                    foreach ($users as $user) {
                        $confLink = env('APP_URL') . '#/conferences/' . $id;
                        $repLink = env('APP_URL') . '#/conferences/' . $id . '/reports/' . $report->id;
                        $message = 'Good afternoon, a new participant ' . Auth::user()->name . ' has joined the conference ' . $user->title . ' (' . '<a href=' . $confLink . '>conference</a>' . ') with a report on the topic ' . $request->thema . ' (' . '<a href=' . $repLink . '>report</a>)</br>
                Presentation time: ' . $startTimeExist;
                        foreach ($user->users as $user) {
                            if ($user->role == 'listener') {
                                array_push($usersEmails, $user->email);
                            }
                        }
                    }
                    foreach ($usersEmails as $email) {
                        dispatch(new SendMailWithQueue($email, $message));
                    }
                }
                break;

            case ('deleted by admin'):
                if (Auth::user()->role === 'admin') {
                    $conferences = Conference::with('reports')->whereId($id)->get();
                    $user = '';
                    $message = '';
                    foreach ($conferences as $conference) {
                        $user = User::whereId($report->user_id)->first();
                        $confLink = env('APP_URL') . '#/conferences/' . $id;
                        $message = 'Good afternoon, at the conference ' . $conference->title . ' (' . '<a href=' . $confLink . '>conference</a>' . ') your report was deleted by the administration.';
                    }
                    dispatch(new SendMailWithQueue($user->email, $message));
                }
                break;

            case ('change report time'):
                if (Auth::user()->role === 'announcer') {
                    $conferences = Conference::with('users')->whereId($id)->get();
                    $message = '';
                    $usersEmails = [];
                    foreach ($conferences as $conference) {
                        $confLink = env('APP_URL') . '#/conferences/' . $id;
                        $repLink = env('APP_URL') . '#/conferences/' . $id . '/reports/' . $report->id;
                        $message = 'Good afternoon, at the conference ' . $conference->title . ' (' . '<a href=' . $confLink . '>conference</a>' . ') participant ' . Auth::user()->name . ' with a report on the topic ' . $report->thema . ' (' . '<a href=' . $repLink . '>report</a>' . ') rescheduled the report.<br>New report time: ' . $request->start_time;
                        foreach ($conference->users as $user) {
                            if ($user->role == 'listener') {
                                array_push($usersEmails, $user->email);
                            }
                        }
                    }
                    foreach ($usersEmails as $email) {
                        dispatch(new SendMailWithQueue($email, $message));
                    }
                }
                break;
        }

    }

    private function isInRange(DateTime $dateToCheck, DateTime $startDate, DateTime $endDate)
    {
        return $dateToCheck >= $startDate && $dateToCheck <= $endDate;
    }

    private function isDateAvailable(Datetime $startTime, Datetime $endTime, int $id)
    {
        $reports = Report::All()->where('conference_id', $id);
        $isDateOk = 0;
        foreach ($reports as $report) {
            $startTimeExist = new Datetime($report->start_time);
            $startTimeExist->setTimezone(new DateTimeZone('GMT'));
            $endTimeExist = new Datetime($report->end_time);
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

    private function NearestTime(int $id)
    {
        $conference = Conference::Find($id)->first();
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

    private function isDateInRangeOfConference(Datetime $startTimeExist, Datetime $endTimeExist, int $id)
    {
        $conference = Conference::Find($id)->first();
        $conStartTime = new DateTime($conference->date . ' ' . $conference->time);
        $conStartTime->setTimezone(new DateTimeZone('GMT'));
        $conEndTime = new DateTime($conference->date . 'T23:59:59.000000Z');
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
