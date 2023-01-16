<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Luis Coaquira',
            'email' => 'kidmeg100@hotmail.com',
            'password' => bcrypt('123456789'),
        ]);
        User::create([
            'name' => 'Ana Calloapaza',
            'email' => 'anamaria@hotmail.com',
            'password' => bcrypt('123456789'),
        ]);
        \App\Models\User::factory(10)->create();
    }
}
