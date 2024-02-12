@extends('website.layouts.app')

@section('title')
    {{ __('applied_jobs') }}
@endsection

@section('main')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row">
                <x-website.candidate.sidebar />
                <div class="col-lg-9">
                    <div class="dashboard-right">
                        <div class="dashboard-right-header rt-mb-32 tw-mt-4 lg:tw-mt-0">
                            <div class="left-text m-0">
                                <h3 class="f-size-18 lh-1 m-0">
                                    {{ __('applied_jobs') }}
                                    <span class="text-gray-400">({{ $appliedJobs->total() }})</span>
                                </h3>
                            </div>
                            <span class="sidebar-open-nav">
                                <i class="ph-list"></i>
                            </span>
                        </div>
                        <div class="db-job-card-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>{{ __('job') }}</th>
                                        <th>{{ __('date_applied') }}</th>
                                        <th>{{ __('status') }}</th>
                                        <th>{{ __('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($appliedJobs->count() > 0)
                                        @foreach ($appliedJobs as $job)
                                            <tr>
                                                <td>
                                                    <div class="rt-single-icon-box tw-gap-5">
                                                        <div class="tw-w-[68px] tw-h-[68px]">
                                                            <img class="tw-w-[68px] tw-h-[68px] tw-rounded-md" src="{{ asset($job->company->logo_url) }}" alt=""
                                                                draggable="false">
                                                        </div>
                                                        <div class="iconbox-content">
                                                            <div class="post-info2">
                                                                <div class="post-main-title">
                                                                    <a class="tw-text-[#18191C] tw-text-base tw-font-medium"
                                                                        href="{{ route('website.job.details', $job->slug) }}">
                                                                        {{ $job->company->user->name }}
                                                                    </a>
                                                                    <span
                                                                        class="badge rounded-pill bg-primary-50 text-primary-500">
                                                                        {{ $job->job_type ? $job->job_type->name : '' }}
                                                                    </span>
                                                                </div>
                                                                <div class="body-font-4 tw-flex tw-gap-4 tw-items-center text-gray-600 pt-2">
                                                                    <span class="info-tools tw-flex tw-items-center tw-gap-1.5">
                                                                        <svg width="18" height="18"
                                                                            viewBox="0 0 18 18" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M15.75 7.5C15.75 12.75 9 17.25 9 17.25C9 17.25 2.25 12.75 2.25 7.5C2.25 5.70979 2.96116 3.9929 4.22703 2.72703C5.4929 1.46116 7.20979 0.75 9 0.75C10.7902 0.75 12.5071 1.46116 13.773 2.72703C15.0388 3.9929 15.75 5.70979 15.75 7.5Z"
                                                                                stroke="#939AAD" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round">
                                                                            </path>
                                                                            <path
                                                                                d="M9 9.75C10.2426 9.75 11.25 8.74264 11.25 7.5C11.25 6.25736 10.2426 5.25 9 5.25C7.75736 5.25 6.75 6.25736 6.75 7.5C6.75 8.74264 7.75736 9.75 9 9.75Z"
                                                                                stroke="#939AAD" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round">
                                                                            </path>
                                                                        </svg>
                                                                        {{ $job->country }}
                                                                    </span>
                                                                    <span class="info-tools tw-flex tw-items-center tw-gap-1.5">
                                                                        <svg width="22" height="22" viewBox="0 0 22 22"
                                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M11 2.0625V19.9375" stroke="#C5C9D6"
                                                                                stroke-width="1.5" stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M15.8125 7.5625C15.8125 7.11108 15.7236 6.66408 15.5508 6.24703C15.3781 5.82997 15.1249 5.45102 14.8057 5.13182C14.4865 4.81262 14.1075 4.55941 13.6905 4.38666C13.2734 4.21391 12.8264 4.125 12.375 4.125H9.28125C8.36957 4.125 7.49523 4.48716 6.85057 5.13182C6.20591 5.77648 5.84375 6.65082 5.84375 7.5625C5.84375 8.47418 6.20591 9.34852 6.85057 9.99318C7.49523 10.6378 8.36957 11 9.28125 11H13.0625C13.9742 11 14.8485 11.3622 15.4932 12.0068C16.1378 12.6515 16.5 13.5258 16.5 14.4375C16.5 15.3492 16.1378 16.2235 15.4932 16.8682C14.8485 17.5128 13.9742 17.875 13.0625 17.875H8.9375C8.02582 17.875 7.15148 17.5128 6.50682 16.8682C5.86216 16.2235 5.5 15.3492 5.5 14.4375"
                                                                                stroke="#C5C9D6" stroke-width="1.5"
                                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                                        </svg>
                                                                        {{ currencyAmountShort($job->min_salary) }} -
                                                                        {{ currencyAmountShort($job->max_salary) }} {{ currentCurrencyCode() }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ date('M d, Y s:m', strtotime($job->pivot->created_at)) }}</td>
                                                <td class="text-{{ $job->deadline_active ? 'success' : 'danger' }}-500">
                                                    @if ($job->deadline_active)
                                                        <div class="tw-flex tw-gap-1.5 tw-items-center">
                                                            <x-svg.active-check-icon />
                                                        {{ __('active') }}
                                                        </div>
                                                    @else
                                                        <div class="tw-flex tw-gap-1.5 tw-items-center">
                                                            <x-svg.expire-cross-icon />
                                                        {{ __('expired') }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="db-job-btn-wrap d-flex justify-content-end">
                                                        <a href="{{ route('website.job.details', $job->slug) }}"
                                                            class="btn bg-gray-50 text-primary-500 rt-mr-8">
                                                            <span class="button-text">
                                                                {{ __('view_details') }}
                                                            </span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">
                                                <x-svg.not-found-icon />
                                                <p class="mt-4">{{ __('no_data_found') }}</p>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="rt-pt-30">
                            @if ($appliedJobs->total() > $appliedJobs->count())
                                <nav>
                                    {{ $appliedJobs->links('vendor.pagination.frontend') }}
                                </nav>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-footer text-center body-font-4 text-gray-500">
            <x-website.footer-copyright />
        </div>
    </div>
@endsection
