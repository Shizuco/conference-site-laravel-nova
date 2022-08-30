<?php
declare (strict_types = 1);
namespace App\Services\Messages;

use App\Models\Conference;
use Auth;
use App\Jobs\SendMailWithQueue;
use App\Services\Messages\SendMessageInterface;

class SendMessageAboutNewListener implements SendMessageInterface
{

    public static function sendMessage($request = 0, int $id = 0, $report = 0)
    {
        $users = Conference::with('users')->whereId($id)->get();
        $usersEmails = [];
        $message = '';
        foreach ($users as $user) {
            $confLink = env('APP_URL') . '#/conferences/' . $id;
            $message = 'Good afternoon, a new listener ' . Auth::user()->name . ' has joined the conference ' . $user->title . ' (' . '<a href=' . $confLink . '>conference</a>' . ')';
            foreach ($user->users as $user) {
                if ($user->role == 'announcer') {
                    array_push($usersEmails, $user->email);
                }
            }
        }
        foreach ($usersEmails as $email) {
            dispatch(new SendMailWithQueue($email, $message));
        }
    }

}