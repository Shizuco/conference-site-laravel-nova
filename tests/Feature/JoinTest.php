<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Conference;
use App\Models\Subscription;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class JoinTest extends TestCase
{
    
    public function test_success_listener_join()
    {
        $user = User::factory(['role' => 'listener',
        'left_joins' => '1'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory()->create();
        $response = $this->post('/api/conferenceJoin/' . $conference->id);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(200);
    }

    public function test_success_announcer_join()
    {
        $user = User::factory(['role' => 'announcer',
        'left_joins' => '1'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory()->create();
        $response = $this->post('/api/conferenceJoin/' . $conference->id);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(200);
    }

    public function test_try_admin_join()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory()->create();
        $response = $this->post('/api/conferenceJoin/' . $conference->id);
        Subscription::where('user_id', $user->id)->delete();
        $conference->delete();
        $response->assertStatus(403);
    }

    public function test_no_left_joins_annoucer_join()
    {
        $user = User::factory(['role' => 'annoucer',
        'left_joins' => '0'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory()->create();
        $response = $this->post('/api/conferenceJoin/' . $conference->id);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(422);
    }

    public function test_no_conference_to_join_annoucer_join()
    {
        $user = User::factory(['role' => 'annoucer',
        'left_joins' => '1'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->post('/api/conferenceJoin/-1');
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $response->assertStatus(404);
    }

    public function test_no_left_joins_listener_join()
    {
        $user = User::factory(['role' => 'listener',
        'left_joins' => '0'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory()->create();
        $response = $this->post('/api/conferenceJoin/' . $conference->id);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(422);
    }

    public function test_no_conference_to_join_listener_join()
    {
        $user = User::factory(['role' => 'listener',
        'left_joins' => '1'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->post('/api/conferenceJoin/-1');
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $response->assertStatus(404);
    }

    public function test_join_without_authenticate_listener_join()
    {
        $conference = Conference::factory()->create();
        $response = $this->post('/api/conferenceJoin/' . $conference->id);
        $response->assertStatus(302);
    }
}
