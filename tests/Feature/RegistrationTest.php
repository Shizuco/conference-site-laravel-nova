<?php

namespace Tests\Feature;

use Tests\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_listener_registration()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'email' => 'randomemail@something.com',
            'password' => '123123123',
            'password_confirmation' => '123123123',
            'role' => 'listener',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);

        $response->assertStatus(201);
        \App\Models\User::where('email', 'randomemail@something.com')->delete();
    }

    public function test_success_announcer_registration()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'email' => 'randomemail@something.com',
            'password' => '123123123',
            'password_confirmation' => '123123123',
            'role' => 'announcer',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);

        $response->assertStatus(201);
        \App\Models\User::where('email', 'randomemail@something.com')->delete();
    }

    public function test_already_exist_registration()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'email' => 'shizucokuro2002@gmail.com',
            'password' => '123123123',
            'password_confirmation' => '123123123',
            'role' => 'listener',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);

        $response
            ->assertStatus(302);
    }

    public function test_password_and_password_confirmation_not_equal_registration()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'email' => 'shi@gmail.com',
            'password' => '123123123',
            'password_confirmation' => '1231231234',
            'role' => 'listener',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);

        $response
            ->assertStatus(302);
    }

    public function test_password_shorter_than_8_registration()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'email' => 'shi@gmail.com',
            'password' => '123123',
            'password_confirmation' => '123123',
            'role' => 'listener',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);

        $response
            ->assertStatus(302);
    }
}
