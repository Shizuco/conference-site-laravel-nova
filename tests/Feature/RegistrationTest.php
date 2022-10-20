<?php

namespace Tests\Feature;

use App\Models\Subscription;
use App\Models\User;
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

        $user = User::where('email', 'randomemail@something.com')->first();
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();

        $response->assertStatus(201);
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

        $user = User::where('email', 'randomemail@something.com')->first();
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();

        $response->assertStatus(201);
    }

    public function test_try_to_register_admin_registration()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'email' => 'randomemail@something.com',
            'password' => '123123123',
            'password_confirmation' => '123123123',
            'role' => 'admin',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);
        $response->assertStatus(403);
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

    public function test_name_is_required()
    {
        $response = $this->post('/api/register', [
            'surname' => 'random',
            'email' => 'randomemail@something.com',
            'password' => '123123123',
            'password_confirmation' => '123123123',
            'role' => 'announcer',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);

        $response->assertStatus(302);
    }

    public function test_surname_is_required()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'email' => 'randomemail@something.com',
            'password' => '123123123',
            'password_confirmation' => '123123123',
            'role' => 'announcer',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);
        $response->assertStatus(302);
    }

    public function test_email_is_required()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'password' => '123123123',
            'password_confirmation' => '123123123',
            'role' => 'announcer',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);
        $response->assertStatus(302);
    }

    public function test_password_is_required()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'email' => 'randomemail@something.com',
            'password_confirmation' => '123123123',
            'role' => 'announcer',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);
        $response->assertStatus(302);
    }

    public function test_role_is_required()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'email' => 'randomemail@something.com',
            'password' => '123123123',
            'password_confirmation' => '123123123',
            'phone' => '380983878221',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);

        $response->assertStatus(302);
    }

    public function test_phone_is_required()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'email' => 'randomemail@something.com',
            'password' => '123123123',
            'password_confirmation' => '123123123',
            'role' => 'announcer',
            'birthday' => '2002-02-02',
            'country' => 'Japan',
        ]);

        $response->assertStatus(302);
    }

    public function test_birthday_is_required()
    {
        $response = $this->post('/api/register', [
            'name' => 'random',
            'surname' => 'random',
            'email' => 'randomemail@something.com',
            'password' => '123123123',
            'password_confirmation' => '123123123',
            'role' => 'announcer',
            'phone' => '380983878221',
            'country' => 'Japan',
        ]);

        $response->assertStatus(302);
    }

    public function test_country_is_required()
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
        ]);

        $response->assertStatus(302);
    }
}
