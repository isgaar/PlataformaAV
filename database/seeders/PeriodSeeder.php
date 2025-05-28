<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('periods')->insert([
            ['start_year' => 2012, 'end_year' => 2015],
            ['start_year' => 2016, 'end_year' => 2019],
            ['start_year' => 2020, 'end_year' => 2023],
        ]);
    }
}
