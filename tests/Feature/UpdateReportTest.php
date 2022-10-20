<?php

namespace Tests\Feature;

use App\Models\Conference;
use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class UpdateReportTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_update_report_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        $reportCreator = User::factory(['role' => 'announcer'])->create();
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
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $reportCreator->id, 'conference_id' => $conference->id])->create();
        $response = $this->put('nova-api/reports/'.$report->id.'?editing=true&editMode=update', [
            'conference_id' => $conference->id,
            'user_id' => $reportCreator->id,
            'category_id' => 1,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 17:00:00',
            'end_time' => '2009-09-09 17:30:00',
            'description' => 'fm[wefm',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        $reportCreator->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(200);
    }

    public function test_success_update_report_by_rigth_announcer()
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
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->put('api/conferences/' . $conference->id . '/reports/' . $report->id, [
            'conference_id' => $conference->id,
            'user_id' => $user->id,
            'category_id' => 1,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 17:00:00',
            'end_time' => '2009-09-09 17:30:00',
            'description' => 'fm[wefm',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        $user->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(200);
    }

    public function test_try_update_report_by_admin()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        $authUser = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $authUser,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->put('api/conferences/' . $conference->id . '/reports/' . $report->id, [
            'conference_id' => $conference->id,
            'user_id' => $user->id,
            'category_id' => 1,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 17:00:00',
            'end_time' => '2009-09-09 17:30:00',
            'description' => 'fm[wefm',
            'presentation' => 'file',
            'isOnline' => false,
        ]);
        $user->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(403);
    }

    public function test_try_update_report_by_listener()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        $authUser = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $authUser,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->put('api/conferences/' . $conference->id . '/reports/' . $report->id, [
            'conference_id' => $conference->id,
            'user_id' => $user->id,
            'category_id' => 1,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 17:00:00',
            'end_time' => '2009-09-09 17:30:00',
            'description' => 'fm[wefm',
            'presentation' => 'file',
            'isOnline' => false,
        ]);
        $user->delete();
        $authUser->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(403);
    }

    public function test_try_update_report_by_simple_announcer()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        $authUser = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $authUser,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->put('api/conferences/' . $conference->id . '/reports/' . $report->id, [
            'conference_id' => $conference->id,
            'user_id' => $user->id,
            'category_id' => 1,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 17:00:00',
            'end_time' => '2009-09-09 17:30:00',
            'description' => 'fm[wefm',
            'presentation' => 'file',
            'isOnline' => false,
        ]);
        $user->delete();
        $authUser->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(403);
    }

    public function test_try_update_report_being_authenticate()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->put('api/conferences/' . $conference->id . '/reports/' . $report->id, [
            'conference_id' => $conference->id,
            'user_id' => $user->id,
            'category_id' => 1,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 17:00:00',
            'end_time' => '2009-09-09 17:30:00',
            'description' => 'fm[wefm',
            'presentation' => 'file',
            'isOnline' => false,
        ]);
        $user->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(302);
    }

    public function test_try_update_unexist_report()
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
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $response = $this->put('api/conferences/' . $conference->id . '/reports/-1', [
            'conference_id' => $conference->id,
            'user_id' => $user->id,
            'category_id' => 1,
            'thema' => 'fjwe[ojgw',
            'start_time' => '2009-09-09 17:00:00',
            'end_time' => '2009-09-09 17:30:00',
            'description' => 'fm[wefm',
            'presentation' => $file,
            'isOnline' => false,
        ]);
        $user->delete();
        $conference->delete();
        $response->assertStatus(404);
    }
}
