<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

class SubscriptionController extends Controller
{
    public function session(string $id)
    {
        $plan = \App\Models\Plan::where('name', $id)->get();

        $subscription = auth()->user()->newSubscription($id, $plan[0]->stripe_plan)
            ->create();
    }
}
