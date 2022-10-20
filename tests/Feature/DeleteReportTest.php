<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Conference;
use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class DeleteReportTest extends TestCase
{
    public function test_success_delete_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        $reportCreator = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $reportCreator->id, 'conference_id' => $conference->id])->create();
        $response = $this->delete('/nova-api/reports?trashed=&resources[]='. $report->id);
        $reportCreator->delete();
        $conference->delete();
        $response->assertStatus(200);
    }

    public function test_success_delete_by_right_announcer()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->delete('/api/conferences/'.$conference->id.'/reports/'.$report->id);
        $user->delete();
        $conference->delete();
        $response->assertStatus(200);
    }

    public function test_delete_by_simple_announcer()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        $reportCreator = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $reportCreator->id, 'conference_id' => $conference->id])->create();
        $response = $this->delete('/api/conferences/'.$conference->id.'/reports/'.$report->id);
        $user->delete();
        $reportCreator->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(403);
    }

    public function test_delete_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        $reportCreator = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $reportCreator->id, 'conference_id' => $conference->id])->create();
        $response = $this->delete('/api/conferences/'.$conference->id.'/reports/'.$report->id);
        $user->delete();
        $reportCreator->delete();
        $report->delete();
        $conference->delete();
        $response->assertStatus(403);
    }

    public function test_delete_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        $reportCreator = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $reportCreator->id, 'conference_id' => $conference->id])->create();
        $response = $this->delete('/api/conferences/'.$conference->id.'/reports/'.$report->id);
        $reportCreator->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(403);
    }

    public function test_delete_being_unauthenticate()
    {
        $reportCreator = User::factory(['role' => 'announcer'])->create();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $reportCreator->id, 'conference_id' => $conference->id])->create();
        $response = $this->delete('/api/conferences/'.$conference->id.'/reports/'.$report->id);
        $reportCreator->delete();
        $conference->delete();
        $response->assertStatus(302);
    }

    public function test_delete_unexist_report()
    {
        $reportCreator = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $reportCreator,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->delete('/api/conferences/'.$conference->id.'/reports/-1');
        $reportCreator->delete();
        $conference->delete();
        $response->assertStatus(404);
    }
}
