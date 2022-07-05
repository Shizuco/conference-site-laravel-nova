<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use App\Models\User;

class UserController extends Controller
{
    
    public function conferencejoin($conference_id, $user_id)
    {
        //$user = auth()->user();
        User::find($user_id)->conferences()->attach($conference_id);

        return [
            'message' => 'joined'
        ];
    }

    public function conferenceOut($conference_id)
    {
        $user = auth()->user();
        $user->conferences()->detach($conference_id);
    }
}