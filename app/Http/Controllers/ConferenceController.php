<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use App\Service\CountryService;
use App\Models\User;
use App\Http\Requests\CreateConferenceRequest;
use App\Http\Requests\UpdateRequest;
use Auth, Redirect, Validator;

class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return json_encode(Conference::all());
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return json_encode(Conference::findOrFail($id));
    }

    public function store(CreateConferenceRequest $request)
    {
        Conference::create($request->validated());
    }

    public function update($id, UpdateRequest $request)
    {   
        Conference::whereId($id)->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Conference::findOrFail($id)->delete();
    }
}