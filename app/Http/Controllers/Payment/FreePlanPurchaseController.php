<?php

namespace App\Http\Controllers\Payment;

use App\Models\Earning;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Modules\Plan\Entities\Plan;
use App\Http\Controllers\Controller;

class FreePlanPurchaseController extends Controller
{
    /**
     * check user is authenticated
     *
     * @return void
     */
    public function  __construct()
    {
        $this->middleware('auth');
    }

    /**
     * check user is authenticated
     *
     * @return void
     */
    public function purchaseFreePlan(Request $request)
    {
        $plan = Plan::findOrFail($request->plan);
        if ($plan->price == 0) {

            $user = auth()->user();
            $company = $user->company;

            // check free plan already buy
            $already_purchase = $this->checkAlreadyPurchase($plan, $company);
            if ($already_purchase) {
                flashWarning("You have purchased this free plan. Can't buy it again .");
                return redirect()->back();
            }

            $this->createPlan($plan, $company);
            $this->makeTransaction($plan);

            flashSuccess('Plan successfully purchased .');
            return redirect()->route('company.plan');
        } else {
            flashWarning("It's not a free plan .");
            return back();
        }
    }

    public function createPlan($plan, $company)
    {
        $user_plan = UserPlan::where('company_id', $company->id)->first();

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
    }

    public function makeTransaction($plan)
    {
        Earning::create([
            'order_id' => uniqid(),
            'transaction_id' => uniqid('tr_'),
            'payment_provider' => 'offline',
            'plan_id' => $plan->id ?? null,
            'company_id' => auth('user')->user()->company->id,
            'amount' => $plan->price,
            'currency_symbol' => config('jobpilot.currency_symbol'),
            'usd_amount' => $plan->price,
            'payment_type' => 'subscription_based',
            'payment_status' => 'paid',
        ]);
    }

    public function checkAlreadyPurchase(Object $plan, Object $company): bool
    {
        $order = Earning::where('company_id', $company->id)->where('plan_id', $plan->id)->where('amount', 0)->first();
        if ($order) {
            return true;
        } else {
            return false;
        }
    }
}
