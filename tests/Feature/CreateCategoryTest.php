<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    public function test_success_create_category_by_admin()
    {
        $user = User::where('role', 'admin')->first();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->post('/nova-api/categories?editing=true&editMode=create', [
            'name' => 'qemjfpejf',
            'category_id' => 1,
        ]);
        Category::where('name', 'qemjfpejf')->delete();
        $response->assertStatus(201);
    }

    public function test_create_category_beeing_unauthnticate()
    {
        $response = $this->post('/nova-api/categories?editing=true&editMode=create', [
            'name' => 'qemjfpejf',
            'category_id' => 1,
        ]);

        $response->assertStatus(401);
    }

    public function test_create_category_by_announcer()
    {
        $user = User::factory(['role' => 'annoucer'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->post('/nova-api/categories?editing=true&editMode=create', [
            'name' => 'qemjfpejf',
            'category_id' => 1,
        ]);
        $user->delete();
        $response->assertStatus(403);
    }

    public function test_create_category_by_listener()
    {
        $user = User::factory(['role' => 'listener'])->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->post('/nova-api/categories?editing=true&editMode=create', [
            'name' => 'qemjfpejf',
            'category_id' => 1,
        ]);
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
        $response = $this->post('/nova-api/categories?editing=true&editMode=create', [
            'category_id' => 1,
        ]);

        $response->assertStatus(302);
    }
}
