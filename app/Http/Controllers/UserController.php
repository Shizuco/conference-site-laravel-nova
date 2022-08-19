<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
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

    public function update(UpdateUserRequest $request)
    {
        $fields = $request->validated();
        if ($request->password !== null) {
            $fields['password'] = bcrypt($request->password);
        } else {
            unset($fields['password']);
        }
        User::whereId(Auth::user()->id)->update($fields);
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('mytasktoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token,
        ];
        return response()->json($response, 201);
    }

    public function isFavorite(int $id)
    {
        return response()->json(Auth::user()->favorite_reports()->where('report_id', $id)->get());
    }

    public function getFavorite()
    {
        return response()->json(Auth::user()->favorite_reports()->get());
    }

    public function favorite(int $reportId)
    {
        Auth::user()->favorite_reports()->attach($reportId);
    }

    public function unfavorite(int $reportId)
    {
        Auth::user()->favorite_reports()->detach($reportId);
    }

}
