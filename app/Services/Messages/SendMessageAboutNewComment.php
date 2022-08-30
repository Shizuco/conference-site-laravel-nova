<?php

namespace App\Services\Messages;

use App\Models\Conference;
use App\Models\Report;
use App\Models\User;
use App\Jobs\SendMailWithQueue;
use App\Services\Messages\SendMessageInterface;

class SendMessageAboutNewComment implements SendMessageInterface
{

    public static function sendMessage($request = 0, int $id = 0, $report = 0)
    {
        $reports = Report::with('users')->whereId($id)->get();
        $user = '';
        $message = '';
        foreach ($reports as $report) {
            $conference = Conference::whereId($report->conference_id)->first();
            $user = User::whereId($report->user_id)->first();
            $confLink = env('APP_URL') . '#/conferences/' . $report->conference_id;
            $repLink = env('APP_URL') . '#/conferences/' . $report->conference_id . '/reports/' . $id;
            $message = 'Good afternoon, at the conference ' . $conference->title . ' (' . '<a href=' . $confLink . '>conference</a>' . '), the user ' . Auth::user()->name .' left a comment on your report ' . $report->thema . '(<a href=' . $repLink . '>report</a>)';
        }
        dispatch(new SendMailWithQueue($user->email, $message));
    }

}