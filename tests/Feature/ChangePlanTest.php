<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChangePlanTest extends TestCase
{

    public function test_success_plan_change()
    {
        $stripe = new \Stripe\StripeClient(
            'sk_test_51LkjfTFS63sMTPizpSCULamSFjkEeDuhstFTN0sHQiZVTGnbRx5OYc34yW8mkTNG9mF6DLV2OSEP5SI1ZtUyUHTJ00dil5HbGC'
        );
        $token = $stripe->paymentMethods->create([
            'type' => 'card',
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 10,
                'exp_year' => 2023,
                'cvc' => '314',
            ],
        ]);
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->postJson('/api/subscribe', [
            'plan' => 'Junior',
            'token' => $token,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $response->assertStatus(200);
    }

    public function test_try_admin_plan_change()
    {
        $user = User::where('email', 'admin@groupbwt.com')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->postJson('/api/subscribe', [
            'plan' => 'Basic',
        ]);
        $response
            ->assertStatus(403);
    }

    public function test_with_no_payment_token_to_basic_success_plan_change()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->postJson('/api/subscribe', [
            'plan' => 'Basic',
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $response
            ->assertStatus(200);
    }

    public function test_with_no_payment_token_with_non_basic_plan_plan_change()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->postJson('/api/subscribe', [
            'plan' => 'Junior',
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $response
            ->assertStatus(422)
            ->assertSeeText('Payment token is invalid');
    }
}
