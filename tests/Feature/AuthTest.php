<?php

declare (strict_types = 1);

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_listener_success_login()
    {
        $user = User::factory(['password' => bcrypt('123123123'), 'role' => 'listener'])->create();
        $response = $this->postJson('/api/login',
            [
                'email' => $user->email,
                'password' => '123123123',
            ]);
            
        $user->delete();
        $response
            ->assertStatus(201);
    }

    public function test_announcer_success_login()
    {
        $user = User::factory(['password' => bcrypt('123123123'), 'role' => 'announcer'])->create();
        $response = $this->postJson('/api/login',
            [
                'email' => $user->email,
                'password' => '123123123',
            ]);
        $user->delete();
        $response
            ->assertStatus(201);
    }

    public function test_bed_creds_password_login()
    {
        $response = $this->postJson('/api/login',
            [
                'email' => 'shizucokuro2002@gmail.com',
                'password' => '12312312',
            ]);

        $error = [
            'errors' => [
                'password' => ['Incorrect login or password'],
            ],
        ];
        $response
            ->assertStatus(422)
            ->assertJson($error);
    }

    public function test_bed_creds_email_login()
    {
        $response = $this->postJson('/api/login',
            [
                'email' => 'sh@gmail.com',
                'password' => '123123123',
            ]);

        $error = [
            'errors' => [
                'email' => ['No account with such email'],
            ],
        ];
        $response
            ->assertStatus(422)
            ->assertJson($error);
    }

    public function test_admin_login()
    {
        $response = $this->postJson('/api/login',
            [
                'email' => 'admin@groupbwt.com',
                'password' => '12345678',
            ]);

        $error = [
            'errors' => [
                'email' => ['Access denied'],
            ],
        ];
        $response
            ->assertStatus(422)
            ->assertJson($error);
    }
}
