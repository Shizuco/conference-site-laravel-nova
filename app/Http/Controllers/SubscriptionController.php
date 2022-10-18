<?php

declare (strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    private function transferToPlan($plan, $token)
    {
        if ($plan != 'Basic' && !$token) {
            abort(422, "Payment token is invalid");
        }
        $user = auth()->user();
        if($user->role == 'admin'){
            abort(403, "Access denined");
        }
        $currentPlan = Subscription::where('user_id', $user->id)->first();
        if ($currentPlan) {
            $user->subscription($currentPlan->name)->cancelNow();
            Subscription::where('user_id', $user->id)->delete();
        }
        $plan = Plan::where('name', $plan)->firstOrFail();
        User::where('id', $user->id)
            ->update([
                "left_joins" => $plan->joins,
            ]);
        if ($token) {
            $subscription = $user->newSubscription($plan, $plan->stripe_plan)
                ->create($token['id'])->cancel();
        } else {
            $subscription = $user->newSubscription($plan, $plan->stripe_plan)
                ->create()->cancel();
        }
    }
}
