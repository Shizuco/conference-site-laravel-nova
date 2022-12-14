<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Conference;
use App\Services\ExportCsvFile;
use App\Services\MakeListenerCsvFile;
use App\Services\Messages\SendMessageAboutNewListener;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $exportCsv;
    protected $listenerCsv;
    protected $newListenerMessage;

    public function __construct(ExportCsvFile $exportCsv, MakeListenerCsvFile $listenerCsv, SendMessageAboutNewListener $newListenerMessage)
    {
        $this->exportCsv = $exportCsv;
        $this->listenerCsv = $listenerCsv;
        $this->newListenerMessage = $newListenerMessage;
    }

    public function conferenceJoin(int $conferenceId)
    {
        if (Auth::user()->role === 'listener') {
            $this->sendMessage($conferenceId);
        }
        if(Auth::user()->role === 'admin'){
            abort(403, 'Access denied');
        }
        if (Conference::where('id', $conferenceId)->firstOrFail()) {
            if (Auth::user()->left_joins != 0) {
                User::where('id', Auth::user()->id)
                    ->update([
                        "left_joins" => Auth::user()->left_joins - 1,
                    ]);
                Auth::user()->conferences()->attach($conferenceId);
            } else {
                abort(422, "No joins left");
            }
        }

    }

    public function getPlan()
    {
        return response()->json(Subscription::where('user_id', auth()->user()->id)->get());
    }

    public function conferenceOut(int $conferenceId)
    {
        $joinCreated = '';
        foreach (Auth::user()->conferences()->get() as $join) {
            if ($join['pivot']['conference_id'] == $conferenceId) {
                $joinCreated = $join['pivot']['created_at'];
                break;
            }
        }
        if (Subscription::where('user_id', Auth::user()->id)->get()[0]['created_at'] < $joinCreated) {
            if (Subscription::where('user_id', Auth::user()->id)->get()[0]['name'] != 'Senior') {

                User::whereId(Auth::user()->id)
                    ->update([
                        "left_joins" => Auth::user()->left_joins + 1,
                    ]);
            }
        }
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
        User::where('id', Auth::user()->id)->update($fields);
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

    public function exportCsv(Request $request, int $id)
    {
        $this->exportCsv->export('listeners', $id);
    }

    public function downloadCsv(int $id)
    {
        return $this->listenerCsv->sendFile($id);
    }

    private function sendMessage(int $id)
    {
        $this->newListenerMessage->sendMessage($id);
    }
}
