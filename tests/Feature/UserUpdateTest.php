<?php

namespace Tests\Feature;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    public function test_success_user_update()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->put('/api/user/change', [
            'name' => 'fwefwef',
            'surname' => 'fewfwef',
            'birthday' => '2009-09-09',
            'country' => 'Japan',
            'email' => 'fwef@mfemwef.com',
            'password' => '123123123',
            'phone' => '380987823445',
        ]);
        $user->delete();
        $response->assertStatus(201);
    }

    public function test_unauthenticate_user_update()
    {
        $user = User::factory()->create();
        $response = $this->put('/api/user/change', [
            'name' => 'fwefwef',
            'surname' => 'fewfwef',
            'birthday' => '2009-09-09',
            'country' => 'Japan',
            'email' => 'fwef@mfemwef.com',
            'password' => '123123123',
            'phone' => '380987823445',
        ]);
        $user->delete();
        $response->assertStatus(302);
    }

    public function test_email_must_be_unique()
    {
        $user = User::factory()->create();
        $userWithEmail = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->put('/api/user/change', [
            'surname' => 'fewfwef',
            'birthday' => '2009-09-09',
            'country' => 'Japan',
            'email' => $userWithEmail->email,
            'password' => '123123123',
            'phone' => '380987823445',
        ]);
        $user->delete();
        $userWithEmail->delete();
        $response->assertStatus(302);
    }
}
