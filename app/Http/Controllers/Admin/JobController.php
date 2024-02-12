<?php

namespace App\Http\Controllers\Admin;

use App\Models\Job;
use App\Models\Tag;
use App\Models\Benefit;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\JobType;
use App\Models\Setting;
use App\Models\Candidate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\SalaryType;
use App\Models\JobCategory;
use Illuminate\Support\Str;
use App\Http\Traits\Jobable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobFormRequest;
use Modules\Location\Entities\Country;
use Illuminate\Support\Facades\Notification;
use App\Notifications\JobApprovalNotification;
use App\Notifications\Website\Candidate\RelatedJobNotification;

class JobController extends Controller
{
    use Jobable;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $request->all();
        abort_if(!userCan('job.view'), 403);

        $query = Job::latest();

        // keyword
        if ($request->title && $request->title != null) {
            $query->where('title', 'LIKE', "%$request->title%");
        }

        // status
        if ($request->status && $request->status != null) {
            if ($request->status != 'all') {
                $query->where('status', $request->status);
            }
        }

        // job_category
        if ($request->job_category && $request->job_category != null) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->job_category);
            });
        }

        // experience
        if ($request->experience && $request->experience != null) {
            $query->whereHas('experience', function ($q) use ($request) {
                $q->where('slug', $request->experience);
            });
        }

        // job_type
        if ($request->job_type && $request->job_type != null) {
            $query->whereHas('job_type', function ($q) use ($request) {
                $q->where('slug', $request->job_type);
            });
        }

        // filter_by
        if ($request->filter_by && $request->filter_by != null) {
            $query->where('status', $request->filter_by);
        }

        $jobs = $query->withoutEdited()->with(['experience', 'job_type'])->paginate(15);
        $jobs->appends($request->all());

        $job_categories = JobCategory::all(['id', 'name', 'slug']);
        $experiences = Experience::all(['id', 'name', 'slug']);
        $job_types = JobType::all(['id', 'name', 'slug']);
        $edited_jobs = Job::edited()->count();

        return view('admin.Job.index', compact('jobs', 'job_categories', 'experiences', 'job_types', 'edited_jobs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(!userCan('job.create'), 403);

        $data['countries'] = Country::all();
        $data['companies'] = Company::all();
        $data['job_category'] = JobCategory::select('id', 'name')->get();
        $data['job_roles'] = JobRole::all();
        $data['experiences'] = Experience::all();
        $data['job_types'] = JobType::all();
        $data['salary_types'] = SalaryType::all();
        $data['educations'] = Education::all();
        $data['benefits'] = Benefit::all(['id', 'name']);
        $data['tags'] = Tag::all(['id', 'name']);

        return view('admin.Job.create', $data);
    }

    public function jobStatusChange(Job $job, Request $request)
    {
        abort_if(!userCan('job.update'), 403);

        $job->update([
            'status' => $request->status,
        ]);

        if ($request->status == 'active') {
            Notification::send($job->company->user, new JobApprovalNotification($job));

            $candidates = Candidate::where('role_id', $job->role_id)->get();
            foreach ($candidates as $candidate) {
                if ($candidate->received_job_alert) {
                    $candidate->user->notify(new RelatedJobNotification($job));
                }
            }
        }

        flashSuccess('Job Status Changed');
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobFormRequest $request)
    {
        abort_if(!userCan('job.create'), 403);

        // Highlight & featured
        $highlight = $request->badge == 'highlight' ? 1 : 0;
        $featured = $request->badge == 'featured' ? 1 : 0;

        $setting = Setting::first();
        $featured_days = $setting->featured_job_days > 0 ? now()->addDays($setting->featured_job_days)->format('Y-m-d'):null;
        $highlight_days = $setting->highlight_job_days > 0 ? now()->addDays($setting->highlight_job_days)->format('Y-m-d'):null;

        $jobCreated = Job::create([
            'title' => $request->title,
            'company_id' => $request->company_id,
            'category_id' => $request->category_id,
            'role_id' => $request->role_id,
            'min_salary' => $request->min_salary,
            'max_salary' => $request->max_salary,
            'salary_type_id' => $request->salary_type,
            'deadline' => Carbon::parse($request->deadline)->format('Y-m-d'),
            'education_id' => $request->education,
            'experience_id' => $request->experience,
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

        // Benefits
        $this->jobBenefitsInsert($request->benefits, $jobCreated);

        // Tags
        $this->jobTagsInsert($request->tags, $jobCreated);

        // <!--  location  -->
        updateMap($jobCreated);

        if ($jobCreated) {
            flashSuccess('Job Created Successfully');
            return redirect()->route('job.index');
        } else {
            flashError('Something went wrong!');
            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        abort_if(!userCan('job.view'), 403);

        return view('admin.Job.show', compact('job'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
        abort_if(!userCan('job.update'), 403);

        $data['companies'] = Company::all();
        $data['job_category'] = JobCategory::select('id', 'name')->get();
        $data['job_roles'] = JobRole::all();
        $data['experiences'] = Experience::all();
        $data['job_types'] = JobType::all();
        $data['salary_types'] = SalaryType::all();
        $data['educations'] = Education::all();
        $data['benefits'] = Benefit::all(['id', 'name']);
        $data['tags'] = Tag::all(['id', 'name']);

        $job->load('tags', 'benefits');
        $data['job'] = $job;

        return view('admin.Job.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(JobFormRequest $request, Job $job)
    {
        abort_if(!userCan('job.update'), 403);

        $highlight = $request->badge == 'highlight' ? 1 : 0;
        $featured = $request->badge == 'featured' ? 1 : 0;

        $job->update([
            'title' => $request->title,
            'company_id' => $request->company_id,
            'category_id' => $request->category_id,
            'role_id' => $request->role_id,
            'min_salary' => $request->min_salary,
            'max_salary' => $request->max_salary,
            'salary_type_id' => $request->salary_type,
            'deadline' => Carbon::parse($request->deadline)->format('Y-m-d'),
            'education_id' => $request->education,
            'experience_id' => $request->experience,
            'job_type_id' => $request->job_type,
            'vacancies' => $request->vacancies,
            'apply_on' => $request->apply_on,
            'apply_email' => $request->apply_email ?? null,
            'apply_url' => $request->apply_url ?? null,
            'description' => $request->description,
            'featured' => $featured,
            'highlight' => $highlight,
            'is_remote' => $request->is_remote ?? 0,
        ]);

        // Benefits
        $this->jobBenefitsSync($request->benefits, $job);

        // Tags
        $this->jobTagsSync($request->tags, $job);

        // <!--  location  -->
        updateMap($job);

        flashSuccess('Job Update Successfully');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        abort_if(!userCan('job.delete'), 403);

        if ($job->delete()) {
            flashSuccess('Job Deleted Successfully');
            return back();
        } else {
            flashError('Something went wrong!');
            return back();
        }
    }

    public function clone(Job $job)
    {
        $newJob = $job->replicate();
        $newJob->created_at = now();
        $newJob->slug = Str::slug($job->title) . '-' . time() . '-' . uniqid();
        $newJob->save();

        flashSuccess('Job Clone Successfully');
        return back();
    }

    /**
     * Edited Approval job list
     */
    public function editedJobList(Request $request)
    {
        // return $request->all();
        abort_if(!userCan('job.view'), 403);

        $query = Job::latest()->edited();

        // keyword
        if ($request->title && $request->title != null) {
            $query->where('title', 'LIKE', "%$request->title%");
        }

        // status
        if ($request->status && $request->status != null) {
            if ($request->status != 'all') {
                $query->where('status', $request->status);
            }
        }

        // job_category
        if ($request->job_category && $request->job_category != null) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->job_category);
            });
        }

        // experience
        if ($request->experience && $request->experience != null) {
            $query->whereHas('experience', function ($q) use ($request) {
                $q->where('slug', $request->experience);
            });
        }

        // job_type
        if ($request->job_type && $request->job_type != null) {
            $query->whereHas('job_type', function ($q) use ($request) {
                $q->where('slug', $request->job_type);
            });
        }

        // filter_by
        if ($request->filter_by && $request->filter_by != null) {
            $query->where('status', $request->filter_by);
        }

        $jobs = $query->with(['experience', 'job_type'])->paginate(15);
        $jobs->appends($request->all());

        $job_categories = JobCategory::all(['id', 'name', 'slug']);
        $experiences = Experience::all(['id', 'name', 'slug']);
        $job_types = JobType::all(['id', 'name', 'slug']);

        return view('admin.Job.edited_jobs', compact('jobs', 'job_categories', 'experiences', 'job_types'));
    }

    /**
     * Show Edited job
     *
     */
    public function editedShow(Job $job)
    {
        $parent_job = Job::FindOrFail($job->parent_job_id);

        return view('admin.Job.show_edited', compact('parent_job', 'job'));
    }

    /**
     * Show Edited job
     *
     */
    public function editedApproved(Job $job)
    {
        $main_job = Job::FindOrFail($job->parent_job_id);

        $main_job->update([
            'title' => $job->title,
            'category_id' => $job->category_id,
            'role_id' => $job->role_id,
            'education_id' => $job->education_id,
            'experience_id' => $job->experience_id,
            'min_salary' => $job->min_salary,
            'max_salary' => $job->max_salary,
            'salary_type_id' => $job->salary_type_id,
            'deadline' => Carbon::parse($job->deadline)->format('Y-m-d'),
            'job_type_id' => $job->job_type_id,
            'vacancies' => $job->vacancies,
            'apply_on' => $job->apply_on,
            'apply_email' => $job->apply_email,
            'apply_url' => $job->apply_url,
            'description' => $job->description,
            'is_remote' => $job->is_remote,

            // map deatils
            'address' => $job->address,
            'neighborhood' => $job->neighborhood,
            'locality' => $job->locality,
            'place' => $job->place,
            'district' => $job->district,
            'postcode' => $job->postcode,
            'region' => $job->region,
            'country' => $job->country,
            'long' => $job->long,
            'lat' => $job->lat,
        ]);

        $job->delete();

        flashSuccess('Job changes applied !');
        return redirect()->route('admin.job.edited.index');
    }
}
