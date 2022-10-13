<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_registration()
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
            'country' => 'Japan' 
        ]);

        $response->assertStatus(201);
    }
}
