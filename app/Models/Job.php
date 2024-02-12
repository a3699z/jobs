<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Tag;
use App\Models\Benefit;
use App\Models\Company;
use App\Models\JobRole;
use App\Models\JobType;
use App\Models\Candidate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profession;
use App\Models\SalaryType;
use App\Models\JobCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Job extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['days_remaining', 'deadline_active', 'can_apply', 'full_address'];

    protected $casts = [
        'bookmarked' => 'boolean',
        'applied' => 'boolean',
        'can_apply' => 'boolean',
        'highlight_until' => 'date:Y-m-d',
        'featured_until' => 'date:Y-m-d',
    ];

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value) . '-' . time() . '-' . uniqid();
    }

    public function getHighlightAttribute($value)
    {
        if ($value) {
            $days =  cache()->remember('highlight_job_days', 60 * 24 * 30, function () {
                return Setting::select('highlight_job_days')->value('highlight_job_days');
            });

            if ($days > 0 && $this->attributes['highlight_until']) {
                $is_active = Carbon::parse($this->attributes['highlight_until'])->isFuture();

                if (!$is_active) {
                    $this->update(['highlight' => 0]);
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function getFeaturedAttribute($value)
    {
        if ($this->attributes['featured']) {
            $days =  cache()->remember('featured_job_days', 60 * 24 * 30, function () {
                return Setting::select('featured_job_days')->value('featured_job_days');
            });

            if ($days > 0 && $this->attributes['featured_until']) {
                $is_active = Carbon::parse($this->attributes['featured_until'])->isFuture();

                if (!$is_active) {
                    $this->update(['featured' => 0]);
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    public function getFullAddressAttribute()
    {
        $country = $this->country;
        $region = $this->region;
        $extra = $region != null ? ' , ' : '';
        return $region . $extra . $country;
    }

    public function getDaysRemainingAttribute()
    {
        return Carbon::now(config('zakirsoft.timezone'))->parse($this->deadline)->diffForHumans(null, true, true, 2);
    }

    public function getCanApplyAttribute()
    {
        if ($this->apply_on === 'app') {
            return true;
        } else {
            return false;
        }
    }

    public function getDeadlineActiveAttribute()
    {
        return Carbon::parse($this->deadline)->format('Y-m-d') >= Carbon::now()->toDateString();
    }

    public function scopeWithoutEdited($query)
    {
        return $query->where('waiting_for_edit_approval', false);
    }

    public function scopeEdited($query)
    {
        return $query->where('waiting_for_edit_approval', true);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOpenPosition($query)
    {
        return $query->where('status', 'active')->where('deadline', '>=', Carbon::now()->toDateString());
    }

    public function scopeCompanyJobs($query, $company_id)
    {
        return $query->where('company_id', $company_id);
    }

    public function scopeNewJobs($query)
    {
        return $query->where('status', 'active')->where('created_at', '>=', Carbon::now()->subDays(7)->toDateString());
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(JobCategory::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(JobRole::class, 'role_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class)->with('user');
    }

    public function bookmarkJobs()
    {
        return $this->belongsToMany(Candidate::class, 'bookmark_candidate_job');
    }

    public function appliedJobs()
    {
        return $this->belongsToMany(Candidate::class, 'applied_jobs')->withPivot('job_id', 'candidate_id')->with('user')->withTimestamps();
    }

    public function experience()
    {
        return $this->belongsTo(Experience::class, 'experience_id');
    }

    public function education()
    {
        return $this->belongsTo(Education::class, 'education_id');
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class, 'profession_id');
    }

    public function job_type()
    {
        return $this->belongsTo(JobType::class, 'job_type_id');
    }

    public function salary_type()
    {
        return $this->belongsTo(SalaryType::class, 'salary_type_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'job_tag');
    }

    public function benefits()
    {
        return $this->belongsToMany(Benefit::class, 'job_benefit');
    }
}
