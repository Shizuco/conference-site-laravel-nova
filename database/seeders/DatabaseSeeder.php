<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \App\Models\User as Admin;
use \App\Models\Conference as Conference;
use Illuminate\Support\Facades\Hash;
use Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create([
            'role' => 'listener',
            'phone' => '380987656428',
            'birthday' => '2002-09-05',
            'country' => 'Japan'
        ]);

        \App\Models\Conference::factory(10)->create([
            'category_id' => '1',
            'date' => '2023-09-05',
            'time' => '14:00:00.000',
            'latitude' => '35.6895000',
            'longitude' => '139.6917100',
            'country' => 'Japan'
        ]);

        \App\Models\Category::factory(1)->create([
            'name' => 'IT'
        ]);

        Admin::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@groupbwt.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'surname' => ' ',
            'phone' => ' ',
            'birthday' => ' ',
            'country' => ' '
         ]);
    }
}
