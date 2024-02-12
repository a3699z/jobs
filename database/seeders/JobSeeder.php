<?php

namespace Database\Seeders;

use Faker\Factory;
use App\Models\Job;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\JobType;
use App\Models\Education;
use App\Models\Experience;
use App\Models\SalaryType;
use App\Models\JobCategory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Google\Service\CloudIAP\Brand;
use Modules\Location\Entities\Country;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Job::factory(50)->create();
        $faker = Factory::create();

        $job_list = json_decode(file_get_contents(base_path('public/dummy/jobs.json')), true);

        for ($i = 0; $i < count($job_list); $i++) {
            $job_data[] = [
                'title'              =>  $job_list[$i]['title'],
                'slug' => Str::slug($job_list[$i]['title']),
                'company_id'        =>  Company::inRandomOrder()->value('id'),
                'category_id'       =>  JobCategory::inRandomOrder()->value('id'),
                'role_id'       =>  JobRole::inRandomOrder()->value('id'),
                'experience_id' => Experience::inRandomOrder()->value('id'),
                'education_id' => Education::inRandomOrder()->value('id'),
                'job_type_id'  => JobType::inRandomOrder()->value('id'),
                'salary_type_id' =>  SalaryType::inRandomOrder()->value('id'),
                'vacancies' =>  $faker->randomElement(['1-2', '2-3', '3-5', '5-10', '10-20']),
                'min_salary'        =>  $job_list[$i]['min_salary'],
                'max_salary'        =>  $job_list[$i]['max_salary'],
                'deadline'          =>  $faker->dateTimeBetween('now', '+07 days'),
                'description'       =>  $job_list[$i]['description'],
                'is_remote'       => $job_list[$i]['is_remote'],
                'status'       =>  'active',
                'featured'       =>  rand(0, 1),
                'highlight'       =>  rand(0, 1),
                'apply_on'       =>  Arr::random(['app', 'email', 'custom_url', 'app', 'app']),
                'apply_email'       =>  'templatecookie@gmail.com',
                'apply_url'       =>  'https://forms.gle/qhUeH3qte7N3rSJ5A',
                'country' => $faker->country(),
                'lat' => $faker->latitude(-90, 90),
                'long' => $faker->longitude(-90, 90)
            ];
        }

        $job_chunks = array_chunk($job_data, ceil(count($job_data) / 3));

        foreach ($job_chunks as $product) {
            Job::insert($product);
        }

        Job::factory(50)->create();


        // Every country has jobs => 5
        $this->everyCountryJobs();
    }


    public function everyCountryJobs()
    {
        $job_list = json_decode(file_get_contents(base_path('public/dummy/jobs.json')), true);
        $faker = Factory::create();

        $countries = Country::active()->get();
        foreach ($countries as $key => $country) {

            for ($i = 0; $i < 5; $i++) {
                $product1 = [
                    'title'              =>  $job_list[$i]['title'],
                    'slug' => Str::slug($job_list[$i]['title']),
                    'company_id'        =>  Company::inRandomOrder()->value('id'),
                    'category_id'       =>  JobCategory::inRandomOrder()->value('id'),
                    'role_id'       =>  JobRole::inRandomOrder()->value('id'),
                    'experience_id' => Experience::inRandomOrder()->value('id'),
                    'education_id' => Education::inRandomOrder()->value('id'),
                    'job_type_id'  => JobType::inRandomOrder()->value('id'),
                    'salary_type_id' =>  SalaryType::inRandomOrder()->value('id'),
                    'vacancies' =>  $faker->randomElement(['1-2', '2-3', '3-5', '5-10', '10-20']),
                    'min_salary'        =>  $job_list[$i]['min_salary'],
                    'max_salary'        =>  $job_list[$i]['max_salary'],
                    'deadline'          =>  $faker->dateTimeBetween('now', '+07 days'),
                    'description'       =>  $job_list[$i]['description'],
                    'is_remote'       => $job_list[$i]['is_remote'],
                    'status'       =>  'active',
                    'featured'       =>  rand(0, 1),
                    'highlight'       =>  rand(0, 1),
                    'apply_on'       =>  Arr::random(['app', 'email', 'custom_url', 'app', 'app']),
                    'apply_email'       =>  'templatecookie@gmail.com',
                    'apply_url'       =>  'https://forms.gle/qhUeH3qte7N3rSJ5A',
                    'country' => $country->name,
                    'lat' => $country->lat,
                    'long' => $country->long
                ];
                Job::insert($product1);
            }
        }
    }
}
