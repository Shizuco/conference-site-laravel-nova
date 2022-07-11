<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conference;
use App\Service\CountryService;
use App\Models\User;
use App\Http\Requests\CreateConferenceRequest;
use App\Http\Requests\UpdateConferenceRequest;
use Auth, Redirect, Validator;

class ConferenceController extends Controller
{
    public function index()
    {
        return response()->json(Conference::all());
    }
    
    public function show(int $id)
    {
        return response()->json(Conference::findOrFail($id));
    }

    public function store(CreateConferenceRequest $request)
    {
        Conference::create($request->validated());
    }

    public function update(int $id, UpdateConferenceRequest $request)
    {   
        Conference::whereId($id)->update($request->validated());
    }

    public function destroy(int $id)
    {
        Conference::findOrFail($id)->delete();
    }
}