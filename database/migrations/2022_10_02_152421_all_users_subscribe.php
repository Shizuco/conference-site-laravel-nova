<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = \App\Models\User::where('role', '!=', 'admin')->get();
        foreach ($users as $user) {
            $plan = \App\Models\Plan::where('name', 'Basic')->get();
            $subscription = $user->newSubscription('Basic', $plan[0]->stripe_plan)
                ->create()->cancel();
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
