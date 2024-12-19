<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('labels')->insert([
            ['name' => 'bug'],
            ['name' => 'question'],
            ['name' => 'enhancement']
        ]);
    }
}
