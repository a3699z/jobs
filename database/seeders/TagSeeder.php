<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = [
            'php',
            'laravel',
            'mysql',
            'job',
            'frontend',
            'backend',
            'bootstrap',
            'team',
            'testing',
            'database',
            'jobs',
            'remote',
            'others',
            'seeker',
            'candidate',
            'company',
            'technology',
            'work'
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }
    }
}
