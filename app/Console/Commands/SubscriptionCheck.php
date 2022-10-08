<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Plan;

class SubscriptionCheck extends Command
{
    protected $signature = 'subscription:check';

    protected $description = 'Check the terms of every user subscriptions';

    public function handle()
    {
        $subs = Subscription::get();
        foreach ($subs as $sub) {
            if (($sub->stripe_status == 'canceled') || (date_timestamp_get(date_create($sub->ends_at)) < date_timestamp_get(date_create()))) {
                $user = User::whereId($sub->user_id)->firstOrFail();
                $currentPlan = Subscription::where('user_id', $user->id)->firstOrFail();
                if ($currentPlan) {
                    Subscription::where('user_id', $user->id)->delete();
                }
                User::whereId($user->id)
                    ->update([
                        "left_joins" => 1,
                    ]);
                $plan = Plan::where('name', 'Basic')->firstOrFail();
                $subscription = $user->newSubscription('Basic', $plan->stripe_plan)
                    ->create()->cancel();
            }
        }
    }
}
