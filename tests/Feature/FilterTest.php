<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FilterTest extends TestCase
{
    public function test_success_filters_by_annoucer()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences', [
            'numberOfReports' => 0,
            'categories' => [],
            'dateFrom' => '2009-09-09',
            'dateTo' => '2012-08-01'
        ]);
        $user->delete();
        $response->assertStatus(200);
    }

    public function test_success_filters_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences', [
            'numberOfReports' => 0,
            'categories' => [],
            'dateFrom' => '2009-09-09',
            'dateTo' => '2012-08-01'
        ]);
        $user->delete();
        $response->assertStatus(200);
    }

    public function test_success_filters_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences', [
            'numberOfReports' => 0,
            'categories' => [],
            'dateFrom' => '2009-09-09',
            'dateTo' => '2012-08-01'
        ]);
        $response->assertStatus(200);
    }

    public function test_success_filters_being_unauthenticate()
    {
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->get('/api/conferences', [
            'numberOfReports' => 0,
            'categories' => [],
            'dateFrom' => '2009-09-09',
            'dateTo' => '2012-08-01'
        ]);
        $response->assertStatus(200);
    }
}
