<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    
    public function conferencejoin($conference_id)
    {
        Auth::user()->conferences()->attach($conference_id);
    }

    public function conferenceOut($conference_id)
    {
        Auth::user()->conferences()->detach($conference_id);
    }
    
    public function getConference($conference_id)
    {
        return json_encode(Auth::user()->conferences()->find($conference_id));
    }
}