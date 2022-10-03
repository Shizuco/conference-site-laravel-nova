<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(2)->create([
            'role' => 'listener',
            'phone' => '380987656428',
            'birthday' => '1986-09-05',
            'country' => 'Japan',
            'left_joins' => 0
        ]);

        \App\Models\User::factory(2)->create([
            'role' => 'listener',
            'phone' => '380987656428',
            'birthday' => '1999-09-05',
            'country' => 'Japan',
            'left_joins' => 3
        ]);
    }
}
