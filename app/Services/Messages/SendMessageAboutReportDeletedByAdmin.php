<?php

namespace App\Services\Messages;

use App\Models\Conference;
use App\Models\User;
use Auth;
use App\Jobs\SendMailWithQueue;
use App\Services\Messages\SendMessageInterface;

class SendMessageAboutReportDeletedByAdmin implements SendMessageInterface
{

    public static function sendMessage($request = 0, int $id = 0, $report = 0)
    {
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
    }

}