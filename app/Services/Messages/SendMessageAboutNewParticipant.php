<?php
declare (strict_types = 1);
namespace App\Services\Messages;

use App\Jobs\SendMailWithQueue;
use App\Models\Conference;
use App\Models\Report;
use App\Services\Messages\SendMessageInterface;
use Auth;
use DateInterval;
use DateTime;
use DateTimeZone;

class SendMessageAboutNewParticipant implements SendMessageInterface
{

    public function sendMessage($request = 0, int $id = 0, $report = 0)
    {
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
    }

}
