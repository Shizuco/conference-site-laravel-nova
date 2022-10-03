<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = \App\Models\User::where('role', '!=', 'admin')->where('birthday', '1986-09-05 00:00:00')->get();
        foreach ($users as $user) {
            $plan = \App\Models\Plan::where('name', 'Basic')->get();
            $subscription = $user->newSubscription('Basic', $plan[0]->stripe_plan)
                ->create()->cancel();
        }

        $users = \App\Models\User::where('role', '!=', 'admin')->where('birthday', '1999-09-05 00:00:00')->get();
        foreach ($users as $user) {
            $plan = \App\Models\Plan::where('name', 'Basic')->get();
            $subscription = $user->newSubscription('Basic', $plan[0]->stripe_plan)
                ->create()->cancelNow();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
