<?php

namespace App\Http\Middleware;

use App\Models\Earning;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasPlanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // check previous url
        $url = str_replace(url('/'), '', url()->previous());
        $path_key = parse_url($url);
        $path = $path_key['path'] ?? '';

        if (auth('user')->check() && auth('user')->user() && auth('user')->user()->role == 'company') {

            if ($path == "/register") {  // from register page . redirect for account complete page first
                return redirect()->route('company.account-progress');
            }

            $user  =  Auth::user();
            $company = $user->company;
            $plan = $company->userPlan;
            if (!$plan) {

                // check company have any pending order
                $check_pending_plan = $this->checkPendingPlan($company);

                $have_any_session = session()->get('success');
                if ($have_any_session) {
                    flashSuccess($have_any_session);
                } elseif ($check_pending_plan) {
                    flashWarning("Your purchased plan order has pending. Please wait until the order is approved .");
                } else {
                    flashWarning("You don't have a chosen plan. Please choose a plan to continue");
                }

                return redirect()->route('website.plan');
            } else {
                return $next($request);
            }
        }
        return $next($request);
    }

    public function checkPendingPlan(Object $company): bool
    {
        $earnings = Earning::where('company_id', $company->id)->where('payment_status', 'unpaid')->first();
        if ($earnings) {
            return true;
        } else {
            return false;
        }
    }
}
