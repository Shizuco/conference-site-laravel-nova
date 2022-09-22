<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        return Plan::all();
    }
}
