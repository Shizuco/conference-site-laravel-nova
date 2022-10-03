<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Plan::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'stripe_plan' => 'price_1LkrNxFS63sMTPizvhAtQ6m2',
            'cost' => '0.00',
            'description' => 'get 1 free join in a month'
            ]);
        \App\Models\Plan::create([
            'name' => 'Junior',
            'slug' => 'junior',
            'stripe_plan' => 'price_1LkrNxFS63sMTPizs53dsQIW',
            'cost' => '15.00',
            'description' => 'get 5 join in a month'
            ]);
        \App\Models\Plan::create([
            'name' => 'Middle',
            'slug' => 'middle',
            'stripe_plan' => 'price_1LkrNyFS63sMTPizlB8Psf6w',
            'cost' => '25.00',
            'description' => 'get 25 join in a month'
            ]);
        \App\Models\Plan::create([
            'name' => 'Senior',
            'slug' => 'senior',
            'stripe_plan' => 'price_1LkrNyFS63sMTPizjrRN0CqC',
            'cost' => '100.00',
            'description' => 'get unlimited join in a month'
            ]);
    }
}
