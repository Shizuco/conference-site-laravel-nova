<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Conference;
use App\Models\Report;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentTest extends TestCase
{
    public function test_success_comment_by_annoucer()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/report/' . $report->id . '/comment', [
            'user_id' => $user->id,
            'report_id' => $report->id,
            'conference_id' => $conference->id,
            'comment' => 'fmewomf[owemf',
        ]);
        Comment::where('comment', 'fmewomf[owemf')->delete();
        $user->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(200);
    }

    public function test_success_update_comment_by_annoucer()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $comment = Comment::factory(['user_id' => $user->id])->create();
        $response = $this->put('/api/conferences/' . $conference->id . '/report/' . $report->id . '/comment/' . $comment->id, [
            'user_id' => $user->id,
            'report_id' => $report->id,
            'conference_id' => $conference->id,
            'comment' => 'fmewomf[owemf',
        ]);
        $comment->delete();
        $user->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(200);
    }

    public function test_success_comment_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/report/' . $report->id . '/comment', [
            'user_id' => $user->id,
            'report_id' => $report->id,
            'conference_id' => $conference->id,
            'comment' => 'fmewomf[owemf',
        ]);
        Comment::where('comment', 'fmewomf[owemf')->delete();
        $user->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(200);
    }

    public function test_success_update_comment_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $comment = Comment::factory(['user_id' => $user->id])->create();
        $response = $this->put('/api/conferences/' . $conference->id . '/report/' . $report->id . '/comment/' . $comment->id, [
            'user_id' => $user->id,
            'report_id' => $report->id,
            'conference_id' => $conference->id,
            'comment' => 'fmewomf[owemf',
        ]);
        $comment->delete();
        $user->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(200);
    }

    public function test_comment_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/report/' . $report->id . '/comment', [
            'user_id' => $user->id,
            'report_id' => $report->id,
            'conference_id' => $conference->id,
            'comment' => 'fmewomf[owemf',
        ]);

        $conference->delete();
        $report->delete();
        $response->assertStatus(403);
    }

    public function test_comment_being_unauthenticate()
    {
        $user = User::factory(['role' => 'listener'])->create();
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/report/' . $report->id . '/comment', [
            'user_id' => $user->id,
            'report_id' => $report->id,
            'conference_id' => $conference->id,
            'comment' => 'fmewomf[owemf',
        ]);
        $user->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(302);
    }

    public function test_comment_is_required()
    {
        $user = User::factory()->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $response = $this->post('/api/conferences/' . $conference->id . '/report/' . $report->id . '/comment', [
            'user_id' => $user->id,
            'report_id' => $report->id,
            'conference_id' => $conference->id,
        ]);
        Comment::where('comment', 'fmewomf[owemf')->delete();
        $user->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(302);
    }

    public function test_comment_is_required_on_update()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $conference = Conference::factory(['date' => '2009-09-09', 'time' => '14:00:00', 'latitude' => '13', 'longitude' => '13', 'country' => 'Japan'])->create();
        $report = Report::factory(['user_id' => $user->id, 'conference_id' => $conference->id])->create();
        $comment = Comment::factory(['user_id' => $user->id])->create();
        $response = $this->put('/api/conferences/' . $conference->id . '/report/' . $report->id . '/comment/' . $comment->id, [
            'user_id' => $user->id,
            'report_id' => $report->id,
            'conference_id' => $conference->id,
        ]);
        $comment->delete();
        $user->delete();
        $conference->delete();
        $report->delete();
        $response->assertStatus(302);
    }
}
