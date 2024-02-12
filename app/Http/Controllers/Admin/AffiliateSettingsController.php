<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AffiliateSettingsController extends Controller
{
    public function index(){
        return view('admin.settings.pages.affiliate');
    }

    public function careerjetUpdate(Request $request)
    {
        $request->validate([
            'default_locale' => 'required',
            'job_limit' => 'required',
        ]);

        setEnv('CARRERJET_ID', $request->careerjet_affiliate_id ?? '');
        checkSetEnv('CARRERJET_LIMIT', $request->job_limit);
        checkSetEnv('CARRERJET_DEFAULT_LOCALE', $request->default_locale);
        setEnv('CARRERJET_ACTIVE', $request->careerjet_status ? 'true' : 'false');

        flashSuccess('Careerjet API Configuration updated!');
        return back();
    }

    public function indeedUpdate(Request $request)
    {
        $request->validate([
            'job_limit' => 'required',
        ]);

        setEnv('INDEED_ID', $request->indeed_affiliate_id ?? '');
        checkSetEnv('INDEED_LIMIT', $request->job_limit);
        setEnv('INDEED_ACTIVE', $request->indeed_status ? 'true' : 'false');

        flashSuccess('Indeed API Configuration updated!');
        return back();
    }
}
