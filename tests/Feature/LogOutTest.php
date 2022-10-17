<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use App\Models\Subscription;

class LogOutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_logout()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->post('/api/logout');
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $response->assertStatus(200);
    }

    public function test_no_token_logout()
    {
        $response = $this->post('/api/logout');
        $response->assertStatus(302);
    }
}
