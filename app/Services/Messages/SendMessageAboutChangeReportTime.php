<?php
declare (strict_types = 1);

namespace App\Services\Messages;

use App\Models\Conference;
use Auth;
use App\Jobs\SendMailWithQueue;
use App\Services\Messages\SendMessageInterface;

class SendMessageAboutChangeReportTime implements SendMessageInterface
{

    public function sendMessage($request = 0, int $id = 0, $report = 0)
    {
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
    }

}