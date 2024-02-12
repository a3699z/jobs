<?php

namespace Database\Seeders;

use App\Models\Benefit;
use Illuminate\Database\Seeder;

class BenefitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $benefits = [
            '400k',
            'Distribution team',
            'Async',
            'Vision insurance',
            'Unlimited vacation',
            'Paid time off',
            '4 day workweek',
            'Company retreats',
            'Coworking budget',
            'Learning budget',
            'Free gym membership',
            'Home office budget',
            'Pay in crypto',
            'Profit sharing',
            'No policies at work',
            'Equity compensation',
        ];

        foreach ($benefits as $benefit) {
            Benefit::create(['name' => $benefit]);
        }
    }
}
