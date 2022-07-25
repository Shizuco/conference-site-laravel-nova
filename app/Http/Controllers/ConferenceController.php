<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use App\Service\CountryService;
use App\Models\Report;
use App\Http\Requests\CreateConferenceRequest;
use App\Http\Requests\UpdateConferenceRequest;
use Auth, Redirect, Validator, Datetime;

class ConferenceController extends Controller
{
    public function index()
    {
        return response()->json(Conference::all());
    }
    
    public function show(int $id)
    {
        $time = $this->hasTime($id);
        $conf = Conference::findOrFail($id);
        $conf['hasTime'] = $time;
        return response()->json($conf);
    }

    public function store(CreateConferenceRequest $request)
    {
        Conference::create($request->validated());
    }

    public function update(UpdateConferenceRequest $request, int $id)
    {   
        Conference::whereId($id)->update($request->validated());
    }

    public function destroy(int $id)
    {
        Conference::findOrFail($id)->delete();
    }

    public function hasTime(int $id){
            $conference = Conference::findOrFail($id);
            $results = Report::orderBy('start_time')->where('conference_id', $id)->get();
            if(count($results) === 0){
                return true;
            }
            $start_time = new Datetime($conference->date . 'T' . $conference->time . 'Z');
            $end_time = 0;
            $hasTime = 0;
            for($a = 0; $a < count($results); $a++){
                if( $a !== 0 && $a !== count($results) - 1){
                    $start_time = new Datetime($results[$a+1]->start_time);
                }
                if($a === count($results) - 1){
                    $end_time = new DateTime($conference->date . ' 23:59:59.000');
                    $start_time = new Datetime($results[$a]->end_time);
                }
                else if($a===0){
                    $end_time = new Datetime($results[$a]->start_time);
                }
                else{
                    $end_time = new Datetime($results[$a]->end_time); 
                }
                $interval = $end_time->diff($start_time);
                $err = (($interval->format('%i')>=10));
                if($err){
                    $hasTime++;
                }
            }  
            return($hasTime!==0)?true:false; 
    }
}