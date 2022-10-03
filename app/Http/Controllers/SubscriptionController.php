<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function session(Request $request)
    {
        $user = auth()->user();
        $currentPlan = Subscription::where('user_id', $user->id)->get()[0]->name;
        if ($currentPlan) {
            $user->subscription($currentPlan)->cancelNow();
            Subscription::where('user_id', $user->id)->delete();
        }
        $plan = Plan::where('name', $request->plan)->get();
        User::whereId($user->id)
            ->update([
                "left_joins" => $plan[0]->joins,
            ]);
        $subscription = $user->newSubscription($request->plan, $plan[0]->stripe_plan)
            ->create($request->token['id'])->cancel();
    }

    public function cancelPlan(Request $request){
        $this->session($request);
    }
}
