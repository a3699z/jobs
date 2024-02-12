<?php

namespace App\Http\Controllers\Admin;

use App\Models\cms;
use App\Models\Cookies;
use App\Models\Setting;
use App\Models\Timezone;
use App\Traits\UploadAble;
use App\Mail\SmtpTestEmail;
use msztorc\LaravelEnv\Env;
use Spatie\Sitemap\Sitemap;
use Illuminate\Http\Request;
use Spatie\Sitemap\Tags\Url;
use Modules\Seo\Entities\Seo;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Modules\Location\Entities\Country;
use Illuminate\Support\Facades\Artisan;
use Modules\Currency\Entities\Currency;
use Modules\Language\Entities\Language;
use Modules\Seo\Entities\SeoPageContent;
use Modules\SetupGuide\Entities\SetupGuide;
use Modules\Currency\Http\Controllers\CurrencyController;
use Modules\Language\Http\Controllers\TranslationController;

class SettingsController extends Controller
{
    use UploadAble;

    public function __construct()
    {
        $this->middleware('access_limitation')->only([
            'generalUpdate',
            'custumCSSJSUpdate',
            'emailUpdate',
            'systemUpdate',
            'cookiesUpdate',
            'seoUpdate',
            'recaptchaUpdate',
            'colorUpdate',
            'layoutUpdate',
        ]);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function general()
    {
        abort_if(!userCan('setting.view'), 403);

        $data['timezones'] = Timezone::all();
        $data['setting'] = Setting::first();
        $data['currencies'] = Currency::all();
        $data['countries'] = Country::all();
        $data['setting'] = Setting::first();

        return view('admin.settings.pages.general', $data);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function theme()
    {
        abort_if(!userCan('setting.view'), 403);

        return view('admin.settings.pages.theme');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function custom()
    {
        abort_if(!userCan('setting.view'), 403);

        return view('admin.settings.pages.custom');
    }

    /**
     * Website Data Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    public function generalUpdate(Request $request)
    {
        abort_if(!userCan('setting.update'), 403);

        if ($request->footer == "true") {
            $this->footerUpdate($request);
        }

        $request->validate([
            'name'      =>  ['required'],
            'email'      =>  ['required'],
            'dark_logo'      =>  ['nullable', 'max:3072'],
            'light_logo'      =>  ['nullable', 'max:3072'],
            'favicon_image'      =>  ['nullable', 'max:1024'],
        ]);

        if ($request->name && $request->name != config('app.name')) {
            setEnv('APP_NAME', $request->name);
        }

        $setting = Setting::first();

        if ($request->hasFile('dark_logo')) {
            $setting['dark_logo'] = uploadFileToPublic($request->dark_logo, 'app/logo');
            deleteFile($setting->dark_logo);
        }

        if ($request->hasFile('light_logo')) {
            $setting['light_logo'] = uploadFileToPublic($request->light_logo, 'app/logo');
            deleteFile($setting->light_logo);
        }

        if ($request->hasFile('favicon_image')) {
            $setting['favicon_image'] = uploadFileToPublic($request->favicon_image, 'app/logo');
            deleteFile($setting->favicon_image);
        }

        $setting->email = $request->email;

        $setting->save();
        SetupGuide::where('task_name', 'app_setting')->update(['status' => 1]);

        return back()->with('success', 'Website setting updated successfully!');
    }


    public function footerUpdate($request)
    {
        $request->validate([
            'footer_phone_no'      =>  ['nullable'],
            'footer_address'      =>  ['nullable'],
            'footer_facebook_link'      =>  ['nullable', 'url'],
            'footer_instagram_link'      =>  ['nullable', 'url'],
            'footer_twitter_link'      =>  ['nullable', 'url'],
            'footer_youtube_link'      =>  ['nullable', 'url'],
        ]);

        $cms = cms::first();

        //Fotter Update
        $cms->footer_phone_no = $request->footer_phone_no;
        $cms->footer_address = $request->footer_address;
        $cms->footer_facebook_link = $request->footer_facebook_link;
        $cms->footer_instagram_link = $request->footer_instagram_link;
        $cms->footer_twitter_link = $request->footer_twitter_link;
        $cms->footer_youtube_link = $request->footer_youtube_link;
        $cms->save();

        return back()->with('success', 'Website Footer Info updated successfully!');
    }

    /**
     * Update website layout
     *
     * @return void
     */
    public function layoutUpdate()
    {
        abort_if(!userCan('setting.update'), 403);

        Setting::first()->update(request()->only('default_layout'));

        return back()->with('success', 'Website layout updated successfully!');
    }

    /**
     * color Data Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    public function colorUpdate()
    {
        abort_if(!userCan('setting.update'), 403);

        Setting::first()->update(request()->only(['sidebar_color', 'nav_color', 'sidebar_txt_color', 'nav_txt_color', 'main_color', 'accent_color', 'frontend_primary_color', 'frontend_secondary_color']));

        SetupGuide::where('task_name', 'theme_setting')->update(['status' => 1]);

        return back()->with('success', 'Color setting updated successfully!');
    }

    /**
     * custom js and css Data Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    public function custumCSSJSUpdate()
    {
        abort_if(!userCan('setting.update'), 403);

        Setting::first()->update(request()->only(['header_css', 'header_script', 'body_script']));

        return back()->with('success', 'Custom css/js updated successfully!');
    }

    /**
     * Mode Update.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return boolean
     */
    public function modeUpdate(Request $request)
    {
        abort_if(!userCan('setting.update'), 403);

        $dark_mode = $request->only(['dark_mode']);
        Setting::first()->update($dark_mode);

        return back()->with('success', 'Theme updated successfully!');
    }

    public function email()
    {
        return view('admin.settings.pages.mail');
    }

    /**
     * Update mail configuration settings on .env file
     *
     * @param Request $request
     * @return void
     */
    public function emailUpdate(Request $request)
    {
        abort_if(!userCan('setting.update'), 403);

        $request->validate([
            'mail_host'     =>  ['required',],
            'mail_port'     =>  ['required', 'numeric'],
            'mail_username'     =>  ['required',],
            'mail_password'     =>  ['required',],
            'mail_encryption'     =>  ['required',],
            'mail_from_name'     =>  ['required',],
            'mail_from_address'     =>  ['required', 'email'],
        ]);

        $data = $request->only(['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_name', 'mail_from_address']);

        foreach ($data as $key => $value) {
            $env = new Env();
            $env->setValue(strtoupper($key), $value);
        }
        SetupGuide::where('task_name', 'smtp_setting')->update(['status' => 1]);

        return back()->with('success', 'Mail configuration update successfully');
    }


    /**
     * Send a test email for check mail configuration credentials
     *
     * @return void
     */
    public function testEmailSent()
    {
        request()->validate(['test_email' => ['required', 'email']]);
        try {
            Mail::to(request()->test_email)->send(new SmtpTestEmail);

            return back()->with('success', 'Test email sent successfully.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Invalid email configuration. Mail send failed.');
        }
    }



    /**
     * View Website mode page
     *
     * @return void
     */
    public function system()
    {
        abort_if(!userCan('setting.view'), 403);

        $timezones = Timezone::all();
        $setting = Setting::first();
        $currencies = Currency::all();

        return view('admin.settings.pages.preference', compact('timezones', 'setting', 'currencies'));
    }

    public function systemUpdate(Request $request)
    {
        abort_if(!userCan('setting.update'), 403);

        if ($request->has('timezone')) {

            $this->timezone($request);
        }
        if ($request->has('code')) {

            (new TranslationController())->setDefaultLanguage($request);
        }

        if ($request->app_debug == 1) {
            Artisan::call('env:set APP_DEBUG=true');
        } else {
            Artisan::call('env:set APP_DEBUG=false');
        }

        if ($request->has('language_changing')) {
            $this->allowLaguageChanage($request);
        }

        if ($request->has('currency')) {

            (new CurrencyController())->defaultCurrency($request);
        }

        // if ($request->google_analytics_id) {
        //     $this->googleAnalytics($request);
        // }
        // if ($request->google_analytics) {
        //     $this->googleAnalytics($request);
        // }

        $setting = Setting::first();
        $setting->update([
            'email_verification' => $request->email_verification ? true : false,
            'employer_auto_activation' => $request->employer_auto_activation ? true : false,
            'candidate_account_auto_activation' => $request->candidate_account_auto_activation ? true : false,
            // job related
            'job_auto_approved' => $request->job_approval ? true : false,
            'edited_job_auto_approved' => $request->edited_job_approval ? true : false,
            'currency_switcher' =>  $request->currency_switcher
        ]);

        // $this->searchIndexing($request);

        return redirect()->back()->with('success', 'App configuration update successfully!');
    }

    public function systemModeUpdate(Request $request)
    {
        if ($request->app_mode == 'live') {
            setEnv('APP_MODE', $request->app_mode);
            return back()->with('success', 'App is now live mode');
        } elseif ($request->app_mode == 'maintenance') {
            setEnv('APP_MODE', $request->app_mode);
            return back()->with('success', 'App is in maintenance mode!');
        } else {
            setEnv('APP_MODE', $request->app_mode);
            return back()->with('success', 'App is in coming soon mode!');
        }
    }

    /**
     * Update search engine indexing setting
     *
     * @return void
     */
    // public function searchIndexing($request)
    // {
    //     abort_if(!userCan('setting.update'), 403);

    //     try {
    //         if ($request->has('search_engine_indexing') && $request->search_engine_indexing == 1) {
    //             $data = "User-agent: *\nDisallow:";
    //         } else {
    //             $data = "User-agent: *\nDisallow: /";
    //         }
    //         file_put_contents(\public_path('robots.txt'), $data);

    //         if ($request->search_engine_indexing == 1) {

    //             Setting::first()->update(['search_engine_indexing' => true]);
    //         } else {
    //             Setting::first()->update(['search_engine_indexing' => false]);
    //         }

    //         return back();
    //     } catch (\Throwable $th) {
    //         return back()->with('error', 'Search Engine Indexing update failed.');
    //     }
    // }


    /**
     * Update google analytics setting
     *
     * @return void
     */
    public function googleAnalytics($request)
    {
        abort_if(!userCan('setting.update'), 403);

        if ($request->google_analytics == 1) {
            Setting::first()->update(['google_analytics' => true]);
        } else {
            Setting::first()->update(['google_analytics' => false]);
        }

        $env = new Env();
        $env->setValue(strtoupper('GOOGLE_ANALYTICS_ID'), request('google_analytics_id', ''));

        session()->put('google_analytics', request('google_analytics', 0));

        return back()->with('success', 'Google Analytics update successfully!');
    }

    /**
     * Update facebook pixel setting
     *
     * @return void
     */
    public function facebookPixel($request)
    {
        abort_if(!userCan('setting.update'), 403);

        $env = new Env();
        $env->setValue(strtoupper('FACEBOOK_PIXEL_ID'), request('facebook_pixel_id', ''));

        if ($request->facebook_pixel == 1) {

            Setting::first()->update([
                'facebook_pixel' => true,
            ]);
        } else {

            Setting::first()->update([
                'facebook_pixel' => false,
            ]);
        }

        session()->put('facebook_pixel', request('facebook_pixel', 0));

        return back()->with('success', 'Facebook Pixel update successfully!');
    }

    public function allowLaguageChanage($request)
    {
        abort_if(!userCan('setting.update'), 403);

        Setting::first()->update([
            'language_changing' => request('language_changing', 0)
        ]);

        flashSuccess('Language changing status changed!');
    }

    public function timezone($request)
    {
        abort_if(!userCan('setting.update'), 403);

        $request->validate([
            'timezone' => "required"
        ]);

        $timezone = $request->timezone;

        if ($timezone && $timezone != config('app.timezone')) {
            envReplace('APP_TIMEZONE', $timezone);

            flashSuccess('Timezone Updated Successfully!');
        }
    }

    public function cookies()
    {
        abort_if(!userCan('setting.view'), 403);

        $cookie = Cookies::firstOrFail();

        return view('admin.settings.pages.cookies', compact('cookie'));
    }

    public function cookiesUpdate(Request $request)
    {
        abort_if(!userCan('setting.update'), 403);

        // validating request data
        $request->validate([
            'cookie_name' => 'required|max:50|string',
            'cookie_expiration' => 'required|numeric|max:365',
            'title' => 'required',
            'description' => 'required',
            'approve_button_text' => 'required|string|max:30',
            'decline_button_text' => 'required|string|max:30',
        ]);
        // updating data to database
        $cookies = Cookies::first();
        $cookies->allow_cookies = request('allow_cookies', 0);
        $cookies->cookie_name = $request->cookie_name;
        $cookies->cookie_expiration = $request->cookie_expiration;
        $cookies->force_consent = request('force_consent', 0);
        $cookies->darkmode = request('darkmode', 0);
        $cookies->title = $request->title;
        $cookies->approve_button_text = $request->approve_button_text;
        $cookies->decline_button_text = $request->decline_button_text;
        $cookies->description = $request->description;
        $cookies->save();
        // flashing success message and redirecting back
        flashSuccess('Cookies settings successfully updated!');
        return back();
    }

    public function seoIndex(Request $request)
    {
        abort_if(!userCan('setting.view'), 403);

        $query = $request->lang_query;
        $seos = Seo::with(['contents' => function ($q) use ($query) {
            if ($query) {
                return $q->where('language_code', $query);
            } else {
                return $q->where('language_code', 'en');
            }
        }])->paginate(20);

        $languages = Language::get(['id', 'code', 'name']);

        return view('admin.settings.pages.seo.index', compact('seos', 'languages'));
    }

    public function seoEdit($page)
    {
        abort_if(!userCan('setting.update'), 403);

        $seo = Seo::FindOrFail($page);
        $en_content = $seo->contents()->where('language_code', 'en')->first();

        if (request('lang_query')) { // if content is not exist then create
            $exist_content = $seo->contents()->where('language_code', request('lang_query'))->first();

            if (!$exist_content) {
                $new_content = $seo->contents()->create([
                    'language_code' => request('lang_query'),
                    'title' => $en_content->title,
                    'description' => $en_content->description,
                    'image' => $en_content->image,
                ]);
            }
        }

        if (request('lang_query')) {
            $content = $seo->contents()->where('language_code', request('lang_query'))->first();
        } else {
            $content = $seo->contents()->first();
        }

        $seo->load('contents');
        $languages = Language::get(['id', 'code', 'name']);

        // $contents = DB::table('seo_page_contents')
        //     ->where('page_id', $seo->id)
        //     ->join('languages', 'seo_page_contents.language_code', '=', 'languages.code')
        //     ->select('seo_page_contents.page_id', 'seo_page_contents.id', 'seo_page_contents.language_code', 'languages.name')
        //     ->get();

        return view('admin.settings.pages.seo.edit', compact('seo', 'languages', 'content'));
    }

    public function seoContentCreate(Request $request)
    {
        abort_if(!userCan('setting.update'), 403);
        $seo = Seo::FindOrFail($request->page_id);
        $exist_content = $seo->contents()->where('language_code', $request->language_code)->first();
        $en_content = $seo->contents()->where('language_code', 'en')->first();

        $content = '';
        if ($exist_content) {
            $content = $exist_content;
        } else {
            $new_content = $seo->contents()->create([
                'language_code' => $request->language_code,
                'title' => $en_content->title,
                'description' => $en_content->description,
                'image' => $en_content->image,
            ]);
            $content = $new_content;
        }

        return redirect()->route('settings.seo.edit', [$seo->id, 'lang_query' => $content->language_code]);
    }

    public function seoContentDelete(Request $request)
    {
        abort_if(!userCan('setting.update'), 403);
        $content = SeoPageContent::FindOrFail($request->content_id);
        $content->delete();

        flashSuccess('success', 'Page translation content delete successfully.');
        return redirect()->route('settings.seo.edit', [$request->page_id, 'lang_query' => 'en']);
    }

    public function seoUpdate(Request $request, SeoPageContent $content)
    {
        abort_if(!userCan('setting.update'), 403);

        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $content->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        if ($request->image != null && $request->hasFile('image')) {
            deleteFile($content->image);

            $path = 'images/seo';
            $image = uploadImage($request->image, $path);

            $content->update([
                'image' => $image,
            ]);
        }

        flashSuccess('Page Meta Content Updated successfully');
        return redirect()->back();
    }


    public function workingProcessUpdate(Request $request)
    {
        abort_if(!userCan('setting.update'), 403);
        session(['tab_part' => 'working_process']);

        $request->validate([
            'working_process_step1_title' => 'required|string',
            'working_process_step1_description' => 'required|string',
            'working_process_step2_title' => 'required|string',
            'working_process_step2_description' => 'required|string',
            'working_process_step3_title' => 'required|string',
            'working_process_step3_description' => 'required|string',
            'working_process_step4_title' => 'required|string',
            'working_process_step4_description' => 'required|string'
        ]);

        $workingProcess = Setting::first();
        $workingProcess->update([
            'working_process_step1_title' => $request->working_process_step1_title,
            'working_process_step1_description' => $request->working_process_step1_description,
            'working_process_step2_title' => $request->working_process_step2_title,
            'working_process_step2_description' => $request->working_process_step2_description,
            'working_process_step3_title' => $request->working_process_step3_title,
            'working_process_step3_description' => $request->working_process_step3_description,
            'working_process_step4_title' => $request->working_process_step4_title,
            'working_process_step4_description' => $request->working_process_step4_description
        ]);

        $workingProcess ? flashSuccess('Work process content updated!') : flashError();
        return back();
    }

    public function generateSitemap()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/home'))
            ->add(Url::create('/jobs'))
            ->add(Url::create('/candidates'))
            ->add(Url::create('/employers'))
            ->add(Url::create('/about'))
            ->add(Url::create('/contact'))
            ->add(Url::create('/login'))
            ->add(Url::create('/register'))
            ->add(Url::create('/faq'))
            ->add(Url::create('/plans'))
            ->add(Url::create('/posts'));

        $sitemap->writeToFile(public_path('sitemap.xml'));

        return back();
    }

    public function recaptchaUpdate(Request $request)
    {
        $request->validate([
            'nocaptcha_key' => 'required',
            'nocaptcha_secret' => 'required',
        ]);

        checkSetEnv('NOCAPTCHA_SITEKEY', $request->nocaptcha_key);
        checkSetEnv('NOCAPTCHA_SECRET', $request->nocaptcha_secret);
        setEnv('NOCAPTCHA_ACTIVE', $request->status ? 'true' : 'false');

        flashSuccess('Recaptcha Configuration updated!');
        return back();
    }

    public function payperjobUpdate(Request $request)
    {
        $request->validate([
            'per_job_price' =>'required',
            'highlight_job_price' =>'required',
            'featured_job_price' =>'required',
            'highlight_job_days' =>'required|numeric|min:0',
            'featured_job_days' =>'required|numeric|min:0',
        ]);

        $setting = Setting::first();

        $setting->update([
            'per_job_active' => $request->status ?? 0,
            'per_job_price' => $request->per_job_price,
            'highlight_job_price' => $request->highlight_job_price,
            'featured_job_price' => $request->featured_job_price,
            'highlight_job_days' => $request->highlight_job_days,
            'featured_job_days' => $request->featured_job_days,
        ]);

        // forget session data
        cache()->forget('highlight_job_days');
        cache()->forget('featured_job_days');

        flashSuccess('Payperjob setting updated!');
        return back();
    }

    /**
     * Upgrade application
     *
     * @return Response
     */
    public function upgrade()
    {
        return view('admin.settings.pages.upgrade-guide');
    }

    /**
     * Upgrade applying
     *
     * @return Response
     */
    public function upgradeApply()
    {
        Artisan::call("migrate");

        flashSuccess('Application Upgrade Successfully');
        return back();
    }
}
