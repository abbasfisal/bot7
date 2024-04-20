<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('cities')->delete();
        $cities = ['Tehran', 'Qom', 'Shiraz'];
        foreach ($cities as $city) {
            City::query()->create(['name' => $city]);
        }
    }
}
