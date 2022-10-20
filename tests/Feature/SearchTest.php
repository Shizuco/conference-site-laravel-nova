<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Conference;
use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class SearchTest extends TestCase
{
    public function test_success_conference_search_by_annoucer()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->get('/api/conferencesByName',[
            'conf_title' => 'a'
        ]);
        $user->delete();
        $response->assertStatus(200);
    }

    public function test_success_reports_search_by_annoucer()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->get('/api/reportsByName',[
            'rep_title' => 'a'
        ]);
        $user->delete();
        $response->assertStatus(200);
    }

    public function test_success_conference_search_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->get('/api/conferencesByName',[
            'conf_title' => 'a'
        ]);
        $user->delete();
        $response->assertStatus(200);
    }

    public function test_success_reports_search_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->get('/api/reportsByName',[
            'rep_title' => 'a'
        ]);
        $user->delete();
        $response->assertStatus(200);
    }

    public function test_conference_search_being_unauthenticate()
    {
        $response = $this->get('/api/conferencesByName',[
            'conf_title' => 'a'
        ]);
        $response->assertStatus(302);
    }

    public function test_reports_search_being_unauthenticate()
    {
        $response = $this->get('/api/reportsByName',[
            'rep_title' => 'a'
        ]);
        $response->assertStatus(302);
    } 
    
    public function test_conference_search_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->get('/api/conferencesByName',[
            'conf_title' => 'a'
        ]);
        $response->assertStatus(403);
    }

    public function test_reports_search_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->get('/api/reportsByName',[
            'rep_title' => 'a'
        ]);
        $response->assertStatus(403);
    }
}
