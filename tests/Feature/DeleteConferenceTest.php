<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Conference;
use Laravel\Sanctum\Sanctum;

class DeleteConference extends TestCase
{
    public function test_success_delete_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->delete('/nova-api/conferences?trashed=&resources[]='.$conference->id);
        $response->assertStatus(200);
    }

    public function test_delete_unexist_conference_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->delete('/nova-api/conferences?trashed=&resources[]=1');
        $response->assertStatus(200);
    }

    public function test_delete_without_authenticate()
    {
        $response = $this->delete('/nova-api/conferences?trashed=&resources[]=1');
        $response->assertStatus(401);
    }

    public function test_delete_by_announcer()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->delete('/nova-api/conferences?trashed=&resources[]='.$conference->id);
        $response->assertStatus(403);
    }

    public function test_delete_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->delete('/nova-api/conferences?trashed=&resources[]='.$conference->id);
        $response->assertStatus(403);
    }
}
