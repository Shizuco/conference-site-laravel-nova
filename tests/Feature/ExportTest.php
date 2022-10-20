<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExportTest extends TestCase
{
    public function test_success_export_conferences_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->get('/api/conferencesDownloadCsv');

        $response->assertStatus(200);
    }

    public function test_success_export_reports_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences/' . $conference->id . '/reportsDownloadCsv');
        $conference->delete();
        $response->assertStatus(200);
    }

    public function test_success_export_comments_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        $reportCreator = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $reportCreator->id, 'conference_id' => $conference->id])->create();
        $response = $this->get('/api/conferences/reports/' . $report->id . '/commentDownloadCsv');
        $reportCreator->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(200);
    }

    public function test_success_export_listeners_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences/' . $conference->id . '/listenersDownloadCsv');
        $conference->delete();
        $response->assertStatus(200);
    }

    public function test_export_conferences_by_annoucer()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->get('/api/conferencesDownloadCsv');
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_export_reports_by_annoucer()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences/' . $conference->id . '/reportsDownloadCsv');
        $conference->delete();
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_export_comments_by_annoucer()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        $reportCreator = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $reportCreator->id, 'conference_id' => $conference->id])->create();
        $response = $this->get('/api/conferences/reports/' . $report->id . '/commentDownloadCsv');
        $reportCreator->delete();
        $conference->delete();
        $report->delete();
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_export_listeners_by_annoucer()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences/' . $conference->id . '/listenersDownloadCsv');
        $conference->delete();
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_export_conferences_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->get('/api/conferencesDownloadCsv');
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_export_reports_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences/' . $conference->id . '/reportsDownloadCsv');
        $conference->delete();
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_export_comments_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        $reportCreator = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $reportCreator->id, 'conference_id' => $conference->id])->create();
        $response = $this->get('/api/conferences/reports/' . $report->id . '/commentDownloadCsv');
        $reportCreator->delete();
        $conference->delete();
        $report->delete();
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_export_listeners_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences/' . $conference->id . '/listenersDownloadCsv');
        $conference->delete();
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_export_conferences_being_unautorizide()
    {
        $response = $this->get('/api/conferencesDownloadCsv');
        $response->assertStatus(302);
    }

    public function test_export_reports_being_unautorizide()
    {
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences/' . $conference->id . '/reportsDownloadCsv');
        $conference->delete();
        $response->assertStatus(302);
    }

    public function test_export_comments_being_unautorizide()
    {
        $reportCreator = User::factory(['role' => 'announcer'])->create();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $reportCreator->id, 'conference_id' => $conference->id])->create();
        $response = $this->get('/api/conferences/reports/' . $report->id . '/commentDownloadCsv');
        $reportCreator->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(302);
    }

    public function test_export_listeners_being_unautorizide()
    {
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences/' . $conference->id . '/listenersDownloadCsv');
        $conference->delete();
        $response->assertStatus(302);
    }
}
