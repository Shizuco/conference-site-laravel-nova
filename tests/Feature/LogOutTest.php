<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogOutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_logout()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
        $response = $this->post('/api/logout');
        $response->assertStatus(200);
    }

    public function test_no_token_logout()
    {
        $response = $this->post('/api/logout');
        $response->assertStatus(302);
    }
}
