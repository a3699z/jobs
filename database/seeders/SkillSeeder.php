<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $skills = [
            'html',
            'css',
            'js',
            'php',
            'laravel',
            'mysql',
            'vuejs',
            'reactjs',
            'nodejs',
            'expressjs',
            'python',
            'django',
        ];

        foreach ($skills as $skill) {
            Skill::create(['name' => $skill]);
        }
    }
}
