<?php

namespace App\Services\Messages;

use App\Models\Conference;
use App\Jobs\SendMailWithQueue;
use App\Services\Messages\SendMessageInterface;

class SendMessageAboutConferenceDeletedByAdmin implements SendMessageInterface
{

    public static function sendMessage($request = 0, int $id = 0, $report = 0)
    {
        $users = Conference::with('users')->whereId($id)->get();
        $usersEmails = [];
        $message = '';
        foreach ($users as $user) {
            $message = 'Good afternoon, unfortunately the conference ' . $user->title . ' has been deleted by the administration.';
            foreach ($user->users as $userEmail) {
                array_push($usersEmails, $userEmail->email);
            }
        }
        foreach ($usersEmails as $email) {
            dispatch(new SendMailWithQueue($email, $message));
        }
    }

}