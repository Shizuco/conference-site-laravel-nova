<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
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

    public function update(RegisterRequest $request)
    {
        $fields = $request->validated();
        $fields['password'] = bcrypt($request->password);
        User::whereId(Auth::user()->id)->update($fields);
    }

    public function isFavorite(int $id){
        return response()->json(Auth::user()->favorite_reports()->where('report_id', $id)->get());
     }

    public function getFavorite(){
       return response()->json(Auth::user()->favorite_reports()->get());
    }

    public function favorite(int $reportId){
        Auth::user()->favorite_reports()->attach($reportId);
    }

    public function unfavorite(int $reportId){
        Auth::user()->favorite_reports()->detach($reportId);
    }
}