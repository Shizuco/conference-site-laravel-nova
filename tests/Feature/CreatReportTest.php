<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Conference;
use App\Models\Subscription;
use App\Models\Report;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;

class CreatReportTest extends TestCase
{
    public function test_success_create_report_by_announcer()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $file = new UploadedFile(
            storage_path() . "/app/" . 'Biology Subject for High School_ Ants Taxonomy by Slidesgo.pptx',
            'Biology Subject for High School_ Ants Taxonomy by Slidesgo.pptx',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation;',
            null,
            TRUE,
            TRUE
        );
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
        $file = new UploadedFile(
            storage_path() . "/app/" . 'Biology Subject for High School_ Ants Taxonomy by Slidesgo.pptx',
            'Biology Subject for High School_ Ants Taxonomy by Slidesgo.pptx',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation;',
            null,
            TRUE,
            TRUE
        );
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
}
