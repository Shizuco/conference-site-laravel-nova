<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function session(Request $request)
    {
        $plan = \App\Models\Plan::where('name', $request->plan)->get();
        $subscription = auth()->user()->newSubscription($request->plan, $plan[0]->stripe_plan)
            ->create();
    }
}
