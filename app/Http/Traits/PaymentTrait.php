<?php

namespace App\Http\Traits;

use Carbon\Carbon;
use App\Models\Job;
use App\Models\Admin;
use App\Models\Earning;
use App\Models\JobRole;
use App\Models\Setting;
use App\Models\UserPlan;
use App\Models\Education;
use App\Models\Experience;
use App\Models\JobCategory;
use App\Http\Traits\Jobable;
use Modules\Plan\Entities\Plan;
use Illuminate\Support\Facades\Notification;
use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Notifications\Admin\NewJobAvailableNotification;
use App\Notifications\Admin\NewPlanPurchaseNotification;
use App\Notifications\Website\Company\JobCreatedNotification;

trait PaymentTrait
{
    use Jobable;

    public function orderPlacing($redirect = true)
    {
        $plan = session('plan');
        $order_amount = session('order_payment');
        $transaction_id = session('transaction_id') ?? uniqid('tr_');
        $job_payment_type = session('job_payment_type') ?? 'package_job';

        $order = Earning::create([
            'order_id' => rand(1000, 999999999),
            'transaction_id' =>  $transaction_id,
            'plan_id' => $plan->id ?? null,
            'company_id' => auth('user')->user()->company->id,
            'payment_provider' => $order_amount['payment_provider'],
            'amount' => $order_amount['amount'],
            'currency_symbol' => $order_amount['currency_symbol'],
            'usd_amount' => $order_amount['usd_amount'],
            'payment_status' => 'paid',
            'payment_type' => $job_payment_type == 'per_job' ? 'per_job_based' : 'subscription_based',
        ]);

        if ($job_payment_type == 'package_job') {
            $user_plan = UserPlan::companyData()->first();
            $company = auth('user')->user()->company;

            if ($user_plan) {
                $user_plan->update([
                    'plan_id' => $plan->id,
                    'job_limit' => $user_plan->job_limit + $plan->job_limit,
                    'featured_job_limit' => $user_plan->featured_job_limit + $plan->featured_job_limit,
                    'highlight_job_limit' => $user_plan->highlight_job_limit + $plan->highlight_job_limit,
                    'candidate_cv_view_limit' => $user_plan->candidate_cv_view_limit + $plan->candidate_cv_view_limit,
                    'candidate_cv_view_limitation' => $plan->candidate_cv_view_limitation,
                ]);
            } else {
                $company->userPlan()->create([
                    'plan_id'  =>  $plan->id,
                    'job_limit'  =>  $plan->job_limit,
                    'featured_job_limit'  =>  $plan->featured_job_limit,
                    'highlight_job_limit'  =>  $plan->highlight_job_limit,
                    'candidate_cv_view_limit'  =>  $plan->candidate_cv_view_limit,
                    'candidate_cv_view_limitation'  =>  $plan->candidate_cv_view_limitation,
                ]);
            }

            if (checkMailConfig()) {
                // make notification to admins for approved
                $admins = Admin::all();
                foreach ($admins as $admin) {
                    Notification::send($admin, new NewPlanPurchaseNotification($admin, $order, $plan, auth('user')->user()));
                }
            }

            storePlanInformation();
        }else{
            return $this->storeJobData();
        }

        $this->forgetSessions();

        if ($redirect) {
            session()->flash('success', 'Plan purchased successfully.');
            return redirect()->route('company.plan')->send();
        }
    }

    private function forgetSessions()
    {
        session()->forget('plan');
        session()->forget('order_payment');
        session()->forget('transaction_id');
        session()->forget('stripe_amount');
        session()->forget('razor_amount');
        session()->forget('job_payment_type');
    }

    private function storeJobData()
    {
        $request = (object) session('job_request');

        // Highlight & featured
        $highlight = $request->badge == 'highlight' ? 1 : 0;
        $featured = $request->badge == 'featured' ? 1 : 0;

        $setting = Setting::first();
        $featured_days = $setting->featured_job_days > 0 ? now()->addDays($setting->featured_job_days)->format('Y-m-d'):null;
        $highlight_days = $setting->highlight_job_days > 0 ? now()->addDays($setting->highlight_job_days)->format('Y-m-d'):null;

         // Job Category
         $job_category_request = $request->category_id;
         $job_category = JobCategory::where('id', $job_category_request)->orWhere('name', $job_category_request)->first();

         if (!$job_category) {
             $job_category = JobCategory::where('name', $job_category_request)->first();

             if (!$job_category) {
                 $job_category = JobCategory::create(['name' => $job_category_request]);
             }
         }

         // Job Role
         $job_role_request = $request->role_id;
         $job_role = JobRole::where('id', $job_role_request)->orWhere('name', $job_role_request)->first();
         if (!$job_role) {
             $job_role = JobRole::where('name', $job_role_request)->first();

             if (!$job_role) {
                 $job_role = JobRole::create(['name' => $job_role_request]);
             }
         }

         // Experience
         $education_request = $request->education;
         $education = Education::where('id', $education_request)->orWhere('name', $education_request)->first();
         if (!$education) {
             $education = Education::where('name', $education_request)->first();

             if (!$education) {
                 $education = Education::create(['name' => $education_request]);
             }
         }

         // Education
         $experience_request = $request->experience;
         $experience = Experience::where('id', $experience_request)->orWhere('name', $experience_request)->first();
         if (!$experience) {
             $experience = Experience::where('name', $experience_request)->first();

             if (!$experience) {
                 $experience = Experience::create(['name' => $experience_request]);
             }
         }

         $jobCreated = Job::create([
             'title' => $request->title,
             'company_id' => auth('user')->user()->company->id,
             'category_id' => $job_category->id,
             'role_id' => $job_role->id,
             'education_id' => $education->id,
             'experience_id' => $experience->id,
             'min_salary' => $request->min_salary,
             'max_salary' => $request->max_salary,
             'salary_type_id' => $request->salary_type,
             'deadline' => Carbon::parse($request->deadline)->format('Y-m-d'),
             'job_type_id' => $request->job_type,
             'vacancies' => $request->vacancies,
             'apply_on' => $request->apply_on,
             'apply_email' => $request->apply_email ?? null,
             'apply_url' => $request->apply_url ?? null,
             'description' => $request->description,
             'featured' => $featured,
             'highlight' => $highlight,
             'featured_until' => $featured_days,
             'highlight_until' => $highlight_days,
             'is_remote' => $request->is_remote ?? 0,
         ]);

         // Location
         updateMap($jobCreated);

          // Benefits
        $this->jobBenefitsInsert($request->benefits, $jobCreated);

        // Tags
        $this->jobTagsInsert($request->tags, $jobCreated);


        if ($jobCreated) {
            if (session('job_payment_type') != 'per_job') {
                $user_plan = auth('user')->user()->company->userPlan()->first();
                $user_plan->job_limit = $user_plan->job_limit - 1;

                if ($featured) {
                    $user_plan->featured_job_limit = $user_plan->featured_job_limit - 1;
                }
                if ($highlight) {
                    $user_plan->highlight_job_limit = $user_plan->highlight_job_limit - 1;
                }
                $user_plan->save();

                storePlanInformation();
            }

            Notification::send(auth('user')->user(), new JobCreatedNotification($jobCreated));

            if (checkMailConfig()) {
                // make notification to admins for approved
                $admins = Admin::all();
                foreach ($admins as $admin) {
                    Notification::send($admin, new NewJobAvailableNotification($admin, $jobCreated));
                }
            }
        }

        $this->forgetSessions();

        session()->flash('success', 'Payment and Job is placed waiting for approval.');
        return redirect()->route('website.job.details', $jobCreated->slug)->send();
    }
}
