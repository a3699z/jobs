<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Faker\Factory;
use App\Models\User;
use App\Models\JobRole;
use App\Models\Candidate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profession;
use App\Models\SocialLink;
use App\Models\ContactInfo;
use App\Models\Nationality;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Modules\Location\Entities\City;
use Modules\Location\Entities\State;
use Modules\Location\Entities\Country;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Candidate
        $candidate = new User();
        $candidate->name = 'John Doe';
        $candidate->username = 'johndoe';
        $candidate->email = 'candidate@mail.com';
        $candidate->password = bcrypt('password');
        $candidate->role = 'candidate';
        $candidate->email_verified_at = Carbon::now();
        $candidate->is_demo_field = true;
        $candidate->save();
        $candidate->candidate()->create([
            'role_id' => JobRole::inRandomOrder()->value('id'),
            'profession_id' => Profession::inRandomOrder()->value('id'),
            'experience_id' => Experience::inRandomOrder()->value('id'),
            'education_id' => Education::inRandomOrder()->value('id'),
            'gender' => 'male',
            'website' => 'https://johndoe.com',
            'title' => 'This is candidate Title !',
            'birth_date' => Carbon::now(),
            'marital_status' => 'married',
            'photo' => 'frontend/assets/images/all-img/model-image.jpg',
            'bio' => 'Sometimes you may wish to stop running validation rules on an attribute after the first validation  failure. To do so, assign the bail rule to the attribute:',
            'profile_complete' => 0,
        ]);
        $candidate->socialInfo()->create([
            'social_media' => 'facebook',
            'url' => 'https://www.facebook.com/zakirsoft',
        ]);
        $candidate->contactInfo()->create([
            'phone' => '+880123456789',
            'secondary_phone' => '+880123456789',
            'email' => 'jhondoe@gmail.com',
            'secondary_email' => 'doejhon@gmail.com',
        ]);

        // Dummy companies
        // Candidate::factory(50)->create();
        $faker = Factory::create();
        $candidate_list = json_decode(file_get_contents(base_path('public/dummy/candidates.json')), true);

        for ($i = 0; $i < count($candidate_list); $i++) {
            $name = $candidate_list[$i]['name'];

            $user = User::create([
                'name' => $name,
                'username' => Str::slug($name),
                'image' => $candidate_list[$i]['image'],
                'email' => $candidate_list[$i]['email'],
                'role' => 'candidate',
                'password' => bcrypt('password'), // password
                'email_verified_at' => now(),
                'is_demo_field' => true,
            ]);

            $candidate_data[] = [
                'user_id' => $user->id,
                'photo' => $candidate_list[$i]['image'],
                'role_id' => JobRole::inRandomOrder()->value('id'),
                'profession_id' => Profession::inRandomOrder()->value('id'),
                'experience_id' => Experience::inRandomOrder()->value('id'),
                'education_id' => Education::inRandomOrder()->value('id'),
                'nationality_id' => Nationality::inRandomOrder()->value('id'),
                'gender' => Arr::random(['male', 'female', 'other']),
                'website' => $faker->url(),
                'title' => $faker->jobTitle(),
                'birth_date' => Carbon::now(),
                'marital_status' => Arr::random(['married', 'single']),
                'bio' => $faker->text(),
                'profile_complete' => 0,
                'country' => $faker->country(),
                'lat' => $faker->latitude(-90, 90),
                'long' => $faker->longitude(-90, 90),
                'status' => Arr::random(['available', 'not_available', 'available_in']),
            ];
        }

        $candidate_chunks = array_chunk($candidate_data, ceil(count($candidate_data) / 3));

        foreach ($candidate_chunks as $candidate) {
            Candidate::insert($candidate);
        }






        // $candidate->socialInfo()->create([
        //     'social_media' => 'facebook',
        //     'url' => 'https://www.facebook.com/zakirsoft',
        // ]);

        // $candidate->contactInfo()->create([
        //     'phone' => '+880123456789',
        //     'secondary_phone' => '+880123456789',
        //     'email' => $this->faker->unique()->safeEmail,
        //     'secondary_email' => $this->faker->unique()->safeEmail,
        // ]);

        // return [
        //     'user_id' => $candidate->id,
        //     'role_id' => JobRole::inRandomOrder()->value('id'),
        //     'profession_id' => Profession::inRandomOrder()->value('id'),
        //     'experience_id' => Experience::inRandomOrder()->value('id'),
        //     'education_id' => Education::inRandomOrder()->value('id'),
        //     'nationality_id' => Nationality::inRandomOrder()->value('id'),
        //     'gender' => Arr::random(['male', 'female', 'other']),
        //     'website' => $this->faker->url(),
        //     'title' => $this->faker->word,
        //     'birth_date' => Carbon::now(),
        //     'marital_status' => Arr::random(['married', 'single']),
        //     'photo' => $this->faker->imageUrl,
        //     'bio' => $this->faker->text(),
        //     'profile_complete' => 0,
        //     'country' => $this->faker->country(),
        //     'lat' => $this->faker->latitude(-90, 90),
        //     'long' => $this->faker->longitude(-90, 90),
        //     'status' => Arr::random(['available', 'not_available', 'available_in']),
        // ];















    }
}
