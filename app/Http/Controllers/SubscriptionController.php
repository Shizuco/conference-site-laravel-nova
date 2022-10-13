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
        if ($request->token) {
            $this->transferToPlan($request->plan, $request->token);
        } else {
            $this->transferToPlan($request->plan, null);
        }
    }

    public function cancelPlan(Request $request)
    {
        if ($request->token) {
            $this->transferToPlan($request->plan, $request->token);
        } else {
            $this->transferToPlan($request->plan, null);
        }
    }

    private function transferToPlan($plan)
    {
        $user = auth()->user();
        $currentPlan = Subscription::where('user_id', $user->id)->firstOrFail();
        if ($currentPlan) {
            $user->subscription($currentPlan->name)->cancelNow();
            Subscription::where('user_id', $user->id)->delete();
        }
        $plan = Plan::where('name', $request->plan)->firstOrFail();
        User::where('id', $user->id)
            ->update([
                "left_joins" => $plan->joins,
            ]);
        if ($request->token) {
            $subscription = $user->newSubscription($request->plan, $plan->stripe_plan)
                ->create($request->token['id'])->cancel();
        } else {
            $subscription = $user->newSubscription($request->plan, $plan->stripe_plan)
                ->create()->cancel();
        }
    }
}
