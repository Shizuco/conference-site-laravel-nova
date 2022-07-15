<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use App\Models\User;
use Auth;

class UserController extends Controller
{
    
    public function conferenceJoin(int $conferenceId)
    {
        Auth::user()->conferences()->attach($conferenceId);
    }

    public function conferenceOut(int $conferenceId)
    {
        Auth::user()->conferences()->detach($conferenceId);
    }
    
    public function getConference(int $conferenceId)
    {
        return json_encode(Auth::user()->conferences()->find($conferenceId));
    }
}