<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravel\Prompts\Table;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        DB::table('students')->delete();

        User::factory(10)->create();
        Student::factory(200)->create();
        $this->call(CitySeeder::class);
    }
}
