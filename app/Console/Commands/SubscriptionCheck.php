<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SubscriptionCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the terms of every user subscriptions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $subs = \App\Models\Subscription::get();
        foreach ($subs as $sub) {
            if (($sub->stripe_status == 'canceled') || (date_timestamp_get(date_create($sub->ends_at)) < date_timestamp_get(date_create()))) {
                $user = \App\Models\User::whereId($sub->user_id)->get()[0];
                $currentPlan = \App\Models\Subscription::where('user_id', $user->id)->get()[0]->name;
                if ($currentPlan) {
                    //$user->subscription($currentPlan)->cancelNow();
                    \App\Models\Subscription::where('user_id', $user->id)->delete();
                }
                \App\Models\User::whereId($user->id)
                    ->update([
                        "left_joins" => 1,
                    ]);
                $plan = \App\Models\Plan::where('name', 'Basic')->get();
                $subscription = $user->newSubscription('Basic', $plan[0]->stripe_plan)
                    ->create()->cancel();
            }
        }
    }
}
