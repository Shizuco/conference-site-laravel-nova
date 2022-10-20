<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreatReportTest extends TestCase
{
    public function test_success_create_report_by_announcer()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $file = $this->file();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        Report::where('user_id', $user->id)->where('conference_id', $conference->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(200);
    }

    public function test_create_report_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'presentation' => 'file',
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(403);
    }

    public function test_create_report_without_authenticate()
    {
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'presentation' => 'file',
            'isOnline' => false,
        ]);
        $conference->delete();
        $response->assertStatus(302);
    }

    public function test_success_create_report_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $reportCreator = User::factory(['role' => 'annoucer'])->create();
        $file = $this->file();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/nova-api/reports?editing=true&editMode=create', [
            'conference_id' => $conference->id,
            'user_id' => $reportCreator->id,
            'category_id' => 1,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $reportCreator->id)->delete();
        Report::where('user_id', $reportCreator->id)->where('conference_id', $conference->id)->delete();
        $reportCreator->delete();
        $conference->delete();
        $response->assertStatus(201);
    }

    public function test_try_to_create_report_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'presentation' => 'file',
            'isOnline' => false,
        ]);
        Report::where('user_id', $user->id)->where('conference_id', $conference->id)->delete();
        $conference->delete();
        $response->assertStatus(403);
    }

    public function test_try_to_create_report_on_unexist_conference()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->post('/api/conferences/-1/reports', [
            'conference_id' => -1,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'presentation' => 'file',
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $response->assertStatus(404);
    }

    public function test_thema_is_required()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $file = $this->file();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(302);
    }

    public function test_sstart_time_is_required()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $file = $this->file();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'thema' => 'fjwe[ojgw',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(302);
    }

    public function test_end_time_is_required()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $file = $this->file();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 15:00:00',
            'description' => 'fm[wefm',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(302);
    }

    public function test_description_is_required()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $file = $this->file();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(302);
    }

    public function test_presentation_is_required()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $file = $this->file();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(302);
    }

    public function test_thema_min_lenght_2()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $file = $this->file();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'thema' => 'f',
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        Report::where('user_id', $user->id)->where('conference_id', $conference->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(302);
    }

    public function test_thema_max_length_255()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $file = $this->file();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00'])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/reports', [
            'conference_id' => $conference->id,
            'thema' => Str::random(260),
            'start_time' => '2009-09-09 15:00:00',
            'end_time' => '2009-09-09 15:30:00',
            'description' => 'fm[wefm',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        Subscription::where('user_id', $user->id)->delete();
        $user->delete();
        $conference->delete();
        $response->assertStatus(302);
    }

    private function file(){
        return new UploadedFile(
            storage_path() . "/app/" . 'Biology Subject for High School_ Ants Taxonomy by Slidesgo.pptx',
            'Biology Subject for High School_ Ants Taxonomy by Slidesgo.pptx',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation;',
            null,
            true,
            true
        );
    }
}
