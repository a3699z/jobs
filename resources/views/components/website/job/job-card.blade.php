<div class="card tw-card jobcardStyle1 {{ $job->highlight ? 'gradient-bg' : '' }}">
    <div class="tw-p-6">
        <div class="tw-mb-5">
            <div class="tw-mb-1.5">
                <a href="{{ route('website.job.details', $job->slug) }}"
                    class="tw-text-[#18191C] tw-text-lg tw-font-medium">
                    {{ $job->title }}
                </a>
            </div>
            <div class="tw-flex tw-gap-2 tw-items-center">
                <span
                    class="tw-text-[#0BA02C] tw-text-[12px] tw-leading-[12px] tw-font-semibold tw-bg-[#E7F6EA] tw-px-2 tw-py-1 tw-rounded-[3px]">{{ $job->job_type ? $job->job_type->name : '' }}</span>
                <span class="tw-text-sm tw-text-[#767F8C]">
                    {{ __('salary') }}: {{ currencyAmountShort($job->min_salary) }} - {{ currencyAmountShort($job->max_salary) }} {{ currentCurrencyCode() }}
                </span>
            </div>
        </div>
        <div class="rt-single-icon-box tw-flex-wrap tw-gap-4">
            <a href="{{ route('website.job.details', $job->slug) }}">
                <div class="tw-w-[56px] tw-h-[56px]">
                    <img class="tw-rounded-lg tw-w-[56px] tw-h-[56px]" src="{{ $job->company->logo_url }}"
                        alt="" draggable="false">
                </div>
            </a>
            <div class="iconbox-content">
                <div class="tw-mb-1 tw-inline-flex">
                    <a href="{{ route('website.job.details', $job->slug) }}"
                        class="tw-text-base tw-font-medium tw-text-[#18191C] tw-card-title">{{ $job->company->user->name }}</a>
                </div>
                <span class="tw-flex tw-items-center tw-gap-1">
                    <i class="ph-map-pin"></i>
                    <span class="tw-location">{{ $job->country }}</span>
                </span>
            </div>
            <div class="">
                <div class="text-primary-500 hoverbg-primary-50 plain-button icon-button">
                    @auth
                        @if (auth()->user()->role == 'candidate')
                            <a href="{{ route('website.job.bookmark', $job->slug) }}" class="tw-text-[#C8CCD1]">
                                @if ($job->bookmarked)
                                    <x-svg.bookmark-icon width="24" height="24" fill="#0A65CC" stroke="#0A65CC" />
                                @else
                                    <x-svg.unmark-icon />
                                @endif
                            </a>
                        @else
                            <button type="button"
                                class="tw-text-[#C8CCD1] hoverbg-primary-50 plain-button icon-button no_permission">
                                <x-svg.unmark-icon />
                            </button>
                        @endif
                    @else
                        <button type="button"
                            class="tw-text-[#C8CCD1] hover:tw-text-[#0A65CC] hoverbg-primary-50 plain-button icon-button login_required">
                            <x-svg.unmark-icon />
                        </button>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
