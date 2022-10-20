<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CommentDeleteTest extends TestCase
{
    public function test_success_delete_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $comment = Comment::factory()->create();
        $response = $this->delete('/nova-api/comments?trashed=&resources[]=' . $comment->id);
        $response->assertStatus(200);
    }

    public function test_delete_by_annoucer()
    {
        $user = User::factory(['role' => 'announcer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $comment = Comment::factory()->create();
        $response = $this->delete('/nova-api/comments?trashed=&resources[]=' . $comment->id);
        $comment->delete();
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_delete_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $comment = Comment::factory()->create();
        $response = $this->delete('/nova-api/comments?trashed=&resources[]=' . $comment->id);
        $comment->delete();
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_delete_being_unauthenticate()
    {
        $comment = Comment::factory()->create();
        $response = $this->delete('/nova-api/comments?trashed=&resources[]=' . $comment->id);
        $comment->delete();
        $response->assertStatus(401);
    }
}
