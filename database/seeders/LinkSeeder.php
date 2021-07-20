<?php

namespace Database\Seeders;

use App\Models\Link;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Link::create([
            "title" => "NASA",
            "url" => "https://www.nasa.gov/",
            "user_id" => "1",
        ]);

        link::factory()
            ->times(249)
            ->create();
    }
}
