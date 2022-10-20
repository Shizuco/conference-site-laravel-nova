<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;

class UpdateCategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_success_update_category_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $category = Category::factory()->create();
        $response = $this->put('/nova-api/categories/'.$category->id.'?editing=true&editMode=update',[
            'name' => 'fpewnfpwef',
            'category_id' => 1
        ]);

        $category->delete();
        $response->assertStatus(200);
    }

    public function test_update_category_beeing_unauthnticate()
    {
        $response = $this->post('/nova-api/categories?editing=true&editMode=create', [
            'name' => 'qemjfpejf',
            'category_id' => 1,
        ]);
        $category = Category::factory()->create();
        $response = $this->put('/nova-api/categories/'.$category->id.'?editing=true&editMode=update',[
            'name' => 'fpewnfpwef',
            'category_id' => 1
        ]);

        $category->delete();
        $response->assertStatus(401);
    }

    public function test_update_category_by_announcer()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $category = Category::factory()->create();
        $response = $this->put('/nova-api/categories/'.$category->id.'?editing=true&editMode=update',[
            'name' => 'fpewnfpwef',
            'category_id' => 1
        ]);

        $category->delete();
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_update_category_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $category = Category::factory()->create();
        $response = $this->put('/nova-api/categories/'.$category->id.'?editing=true&editMode=update',[
            'name' => 'fpewnfpwef',
            'category_id' => 1
        ]);

        $category->delete();
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_name_is_required()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $category = Category::factory()->create();
        $response = $this->put('/nova-api/categories/'.$category->id.'?editing=true&editMode=update',[
            'category_id' => 1
        ]);

        $category->delete();

        $response->assertStatus(302);
    }
}
