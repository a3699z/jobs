<?php

namespace Database\Seeders;

use Database\Seeders\CmsSeeder;
use Database\Seeders\JobSeeder;
use Database\Seeders\TagSeeder;
use Illuminate\Database\Seeder;
use App\Models\ApplicationGroup;
use Database\Seeders\AdminSeeder;
use Database\Seeders\SkillSeeder;
use Database\Seeders\MasterSeeder;
use Database\Seeders\BenefitSeeder;
use Database\Seeders\CompanySeeder;
use Database\Seeders\CookiesSeeder;
use Database\Seeders\EarningSeeder;
use Database\Seeders\JobRoleSeeder;
use Database\Seeders\JobTypeSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\TeamSizeSeeder;
use Database\Seeders\CandidateSeeder;
use Database\Seeders\EducationSeeder;
use Database\Seeders\CmsContentSeeder;
use Database\Seeders\CompanyBookmarks;
use Database\Seeders\ExperienceSeeder;
use Database\Seeders\ProfessionSeeder;
use Database\Seeders\SalaryTypeSeeder;
use Database\Seeders\AdminSearchSeeder;
use Database\Seeders\JobCategorySeeder;
use Database\Seeders\NationalitySeeder;
use Database\Seeders\CandidateBookmarks;
use Database\Seeders\IndustryTypeSeeder;
use Database\Seeders\ManualPaymentSeeder;
use Database\Seeders\CandidateSkillSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\WebsiteSettingSeeder;
use Database\Seeders\CandidateResumeSeeder;
use Database\Seeders\ApplicationGroupSeeder;
use Database\Seeders\OrganizationTypeSeeder;
use Database\Seeders\BenefitPermissionSeeder;
use Database\Seeders\CandidateLanguageSeeder;
use Database\Seeders\CandidateEducationSeeder;
use Database\Seeders\CandidateAppliedJobSeeder;
use Database\Seeders\CandidateExperienceSeeder;
use Modules\Faq\Database\Seeders\FaqCategorySeeder;
use Modules\Faq\Database\Seeders\FaqDatabaseSeeder;
use Modules\Seo\Database\Seeders\SeoDatabaseSeeder;
use Modules\Blog\Database\Seeders\BlogDatabaseSeeder;
use Modules\Plan\Database\Seeders\PlanDatabaseSeeder;
use Modules\Currency\Database\Seeders\CurrencyDatabaseSeeder;
use Modules\Language\Database\Seeders\LanguageDatabaseSeeder;
use Modules\Location\Database\Seeders\LocationDatabaseSeeder;
use Modules\SetupGuide\Database\Seeders\SetupGuideDatabaseSeeder;
use Modules\Testimonial\Database\Seeders\TestimonialDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // For Packaging
        $this->packagingVersion();

        // For Development
        // $this->developmentVersion();
    }

    private function packagingVersion()
    {
        $this->call([
            SettingSeeder::class,
            LocationDatabaseSeeder::class,
            WebsiteSettingSeeder::class,
            CmsSeeder::class,
            SeoDatabaseSeeder::class,
            SetupGuideDatabaseSeeder::class,
            CookiesSeeder::class,
            MasterSeeder::class,
            ApplicationGroupSeeder::class,
            CmsContentSeeder::class,

            // Attribute
            ProfessionSeeder::class,
            JobTypeSeeder::class,
            JobCategorySeeder::class,
            JobRoleSeeder::class,
            ExperienceSeeder::class,
            EducationSeeder::class,
            SalaryTypeSeeder::class,
            IndustryTypeSeeder::class,
            NationalitySeeder::class,
            OrganizationTypeSeeder::class,
            TeamSizeSeeder::class,

            // Candidate Skills and Language
            SkillSeeder::class,
            CandidateSkillSeeder::class,
            CandidateLanguageSeeder::class,

            // jobs tags, benefits
            TagSeeder::class,
            BenefitSeeder::class,
        ]);
    }

    private function developmentVersion()
    {
        $this->call([
            // RolePermissionSeeder::class, // => it's already call in migration
            LocationDatabaseSeeder::class,
            PlanDatabaseSeeder::class,
            ManualPaymentSeeder::class,

            // Setting
            SettingSeeder::class,
            WebsiteSettingSeeder::class,
            CmsSeeder::class,
            CurrencyDatabaseSeeder::class,
            SeoDatabaseSeeder::class,
            LanguageDatabaseSeeder::class,
            SetupGuideDatabaseSeeder::class,
            AdminSearchSeeder::class,
            CookiesSeeder::class,

            // Job Attributes
            EducationSeeder::class,
            ExperienceSeeder::class,
            JobTypeSeeder::class,
            JobRoleSeeder::class,
            SalaryTypeSeeder::class,
            TeamSizeSeeder::class,
            NationalitySeeder::class,
            OrganizationTypeSeeder::class,
            ProfessionSeeder::class,
            IndustryTypeSeeder::class,
            JobCategorySeeder::class,

            // Company, candidate and admin
            CompanySeeder::class,
            CandidateSeeder::class,
            AdminSeeder::class,

            // Candidate Cv
            CandidateResumeSeeder::class,

            // Jobs and bookmark
            ApplicationGroupSeeder::class,
            JobSeeder::class,
            CandidateBookmarks::class,
            CandidateAppliedJobSeeder::class,
            CompanyBookmarks::class,

            // Others
            TestimonialDatabaseSeeder::class,
            BlogDatabaseSeeder::class,
            FaqCategorySeeder::class,
            FaqDatabaseSeeder::class,
            EarningSeeder::class,
            CmsContentSeeder::class,

            // Candidate Skills, Language, Experience and Education
            SkillSeeder::class,
            CandidateSkillSeeder::class,
            CandidateLanguageSeeder::class,
            CandidateExperienceSeeder::class,
            CandidateEducationSeeder::class,

             // jobs tags, benefits
             TagSeeder::class,
             BenefitSeeder::class,
             JobTagSeeder::class,
             JobBenefitSeeder::class,
        ]);
    }
}
