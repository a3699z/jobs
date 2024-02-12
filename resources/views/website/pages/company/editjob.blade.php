@extends('website.layouts.app')

@section('title')
    {{ __('post_job') }}
@endsection

@section('main')
    <div class="dashboard-wrapper">
        <div class="container">
            <div class="row">
                {{-- Sidebar --}}
                <x-website.company.sidebar />
                <div class="col-lg-9">
                    <div class="dashboard-right">
                        <div class="dashboard-right-header">
                            <span class="sidebar-open-nav">
                                <i class="ph-list"></i>
                            </span>
                        </div>
                        <h2 class="tw-text-2xl tw-font-medium tw-text-[#18191C] tw-mb-8">
                            {{ __('edit_job') }}
                        </h2>
                        <form action="{{ route('company.job.update', $job->slug) }}" method="POST" class="rt-from">
                            @csrf
                            @method('PUT')
                            <div class="post-job-item rt-mb-15">
                                <div class="row">
                                    <div class="col-lg-8 rt-mb-20">
                                        <x-forms.label name="job_title" :required="true" class="tw-text-sm tw-mb-2" />
                                        <input value="{{ $job->title }}" name="title"
                                            class="form-control @error('title') is-invalid @enderror" type="text"
                                            placeholder="{{ __('job_title') }}" id="m">
                                        @error('title')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 rt-mb-20 col-md-4">
                                        <x-forms.label name="job_category" :required="true" />
                                        <select
                                            class="w-100-p select2-taggable form-control @error('category_id') is-invalid @enderror"
                                            name="category_id">
                                            @foreach ($jobCategories as $category)
                                                <option {{ $job->category_id == $category->id ? 'selected' : '' }}
                                                    value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-8 rt-mb-20 col-md-8">
                                        <x-forms.label name="tags" :required="true" class="tw-text-sm tw-mb-2" />
                                        <select
                                            class="w-100-p rt-selectactive select2-taggable form-control @error('tags') is-invalid @enderror"
                                            name="tags[]" multiple>
                                            @foreach ($tags as $tag)
                                                <option
                                                    @foreach ($job->tags as $job_tag)
                                                    {{ $job_tag->id == $tag->id ? 'selected' : '' }} @endforeach
                                                    value="{{ $tag->id }}">{{ $tag->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('tags')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 rt-mb-20 col-md-4">
                                        <x-forms.label name="job_role" :required="true" class="tw-text-sm tw-mb-2" />
                                        <select
                                            class="w-100-p select2-taggable form-control @error('role_id') is-invalid @enderror"
                                            name="role_id">
                                            @foreach ($roles as $role)
                                                <option {{ $job->role_id == $role->id ? 'selected' : '' }}
                                                    value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="post-job-item rt-mb-15">
                                <h4 class="f-size-18 ft-wt-5 rt-mb-20 lh-1">{{ __('salary') }}</h4>
                                <div class="row">
                                    <div class="rt-mb-20 col-md-4">
                                        <x-forms.label name="min_salary" :required="false" class="tw-text-sm tw-mb-2" />
                                        <div class="position-relative">
                                            <input step="0.01" value="{{ $job->min_salary }}"
                                                class="form-control @error('min_salary') is-invalid @enderror"
                                                name="min_salary" type="number" placeholder="{{ __('min_salary') }}"
                                                id="m">
                                            <div class="usd">{{ $currency_symbol }}</div>
                                            @error('min_salary')
                                                <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="rt-mb-20 col-md-4">
                                        <x-forms.label name="max_salary" :required="false" class="tw-text-sm tw-mb-2" />
                                        <div class="position-relative">
                                            <input step="0.01" value="{{ $job->max_salary }}"
                                                class="form-control @error('max_salary') is-invalid @enderror"
                                                name="max_salary" type="number" placeholder="{{ __('max_salary') }}"
                                                id="m">
                                            <div class="usd">{{ $currency_symbol }}</div>
                                            @error('max_salary')
                                                <span class="error invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-4 rt-mb-20 col-md-6">
                                        <x-forms.label name="{{ __('salary_type') }}" :required="true"
                                            class="tw-text-sm tw-mb-2" />
                                        <select
                                            class="rt-selectactive form-control @error('salary_type') is-invalid @enderror w-100-p"
                                            name="salary_type">
                                            @foreach ($salary_types as $type)
                                                <option {{ $job->salary_type_id == $type->id ? 'selected' : '' }}
                                                    value="{{ $type->id }}">
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('salary_type')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="post-job-item rt-mb-15">
                                <h4 class="f-size-18 ft-wt-5 rt-mb-20 lh-1">{{ __('advance_information') }}</h4>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 rt-mb-20">
                                        <x-forms.label name="education" :required="true" class="tw-text-sm tw-mb-2" />
                                        <select
                                            class="select2-taggable form-control @error('education') is-invalid @enderror w-100-p"
                                            name="education">
                                            @foreach ($educations as $education)
                                                <option {{ $job->education_id == $education->id ? 'selected' : '' }}
                                                    value="{{ $education->id }}">
                                                    {{ $education->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('education')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-md-6 rt-mb-20">
                                        <x-forms.label name="experience" :required="true" class="tw-text-sm tw-mb-2" />
                                        <select
                                            class="select2-taggable form-control @error('experience') is-invalid @enderror w-100-p"
                                            name="experience">
                                            @foreach ($experiences as $experience)
                                                <option {{ $job->experience_id == $experience->id ? 'selected' : '' }}
                                                    value="{{ $experience->id }}">
                                                    {{ $experience->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('experience')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-4 col-md-6 rt-mb-20">
                                        <x-forms.label name="job_type" :required="true" class="tw-text-sm tw-mb-2" />
                                        <select
                                            class="rt-selectactive form-control @error('job_type') is-invalid @enderror w-100-p"
                                            name="job_type">
                                            @foreach ($job_types as $job_type)
                                                <option {{ $job->job_type_id == $job_type->id ? 'selected' : '' }}
                                                    value="{{ $job_type->id }}">
                                                    {{ $job_type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('job_type')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-md-6 rt-mb-20">
                                        <x-forms.label name="vacancies" :required="true" class="tw-text-sm tw-mb-2" />
                                        <input value="{{ $job->vacancies }}" name="vacancies" type="text"
                                            placeholder="{{ __('vacancies') }}"
                                            class="form-control @error('vacancies') is-invalid @enderror" id="vacancies">
                                        @error('vacancies')
                                            <span class="error invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-lg-6 col-md-6 rt-mb-20">
                                        <x-forms.label name="deadline_expired" :required="true"
                                            class="tw-text-sm tw-mb-2" />
                                        <div class="fromGroup">
                                            <div class="form-control-icon date datepicker">
                                                <input value="{{ date('d-m-Y', strtotime($job->deadline)) }}"
                                                    name="deadline"
                                                    class="form-control @error('deadline') is-invalid @enderror"
                                                    type="text" id="date" placeholder="d/m/y">
                                                <span class="input-group-addon has-badge">
                                                    <span @error('deadline') rt-mr-12 @enderror>
                                                        <x-svg.calendar-icon />
                                                    </span>
                                                </span>
                                                @error('deadline')
                                                    <span class="error invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12 rt-mb-15">
                                    @php
                                        $map = setting('default_map');
                                    @endphp
                                    <div class="location-wrapper">
                                        <div class="row">
                                            <div class="col-12">
                                                <h2>
                                                    {{ __('location') }} <span class="text-danger">*</span>
                                                    <small class="h6">
                                                        ({{ __('click_to_add_a_pointer') }})
                                                    </small>
                                                </h2>
                                            </div>
                                            <div class="col-md-12 col-sm-12 rt-mb-24">
                                                <x-website.map.map-warning />

                                                <div class="map mymap {{ $map == 'map-box' ? '' : 'd-none' }}"
                                                    id='map-box'>
                                                </div>
                                                <div id="google-map-div"
                                                    class="{{ $map == 'google-map' ? '' : 'd-none' }}">
                                                    <input id="searchInput" class="mapClass" type="text"
                                                        placeholder="{{ __('enter_location') }}">
                                                    <div class="map mymap" id="google-map"></div>
                                                </div>
                                                <div id="google-map-div"
                                                    class="{{ $map == 'google-map' ? '' : 'd-none' }}">
                                                    <input id="searchInput" class="mapClass" type="text"
                                                        placeholder="{{ __('enter_location') }}">
                                                    <div class="map mymap" id="google-map"></div>
                                                </div>
                                                <div class="{{ $map == 'leaflet' ? '' : 'd-none' }}">
                                                    <input type="text" autocomplete="off" id="leaflet_search"
                                                        placeholder="{{ __('enter_city_name') }}" class="full-width" />
                                                    <br>
                                                    <div id="leaflet-map"></div>
                                                </div>
                                                @error('location')
                                                    <span class="ml-3 text-md text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-12 mt-4 custom-checkbox-wrap">
                                                <label class="main tw-text-sm"
                                                    for="remoteWork">{{ __('fully_remote_position') }}-<span
                                                        class="tw-font-medium">{{ __('worldwide') }}</span>
                                                    <input type="checkbox" name="is_remote" id="remoteWork"
                                                        value="1" {{ $job->is_remote ? 'checked' : '' }}>
                                                    <span class="custom-checkbox"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="post-job-item rt-mb-32">
                                <h4 class="f-size-18 ft-wt-5 rt-mb-20 lh-1">{{ __('benefits') }}</h4>
                                <div class="benefits-tags">
                                    @foreach ($benefits as $benefit)
                                        <label for="benefit_{{ $benefit->id }}">
                                            <input
                                                @foreach ($job->benefits as $job_benefit)
                                            {{ $job_benefit->id == $benefit->id ? 'checked' : '' }} @endforeach
                                                type="checkbox" id="benefit_{{ $benefit->id }}" name="benefits[]"
                                                value="{{ $benefit->id }}">
                                            <span>{{ $benefit->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('benefits')
                                    <span class="error invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="post-job-item rt-mb-32">
                                <h4 class="f-size-18 ft-wt-5 tw-mb-3 lh-1">{{ __('job_description') }}</h4>
                                <div class="col-md-12">
                                    <textarea id="default" class="form-control @error('description') is-invalid @enderror" name="description">{{ $job->description }}</textarea>
                                    @error('description')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row tw-mb-8">
                                <div class="col-12">
                                    <div class="applied-job-on">
                                        <div class="row">
                                            <h2>{{ __('apply_job_on') }}:</h2>
                                            <!-- apply_on -->
                                            <div id="applied_on_app"
                                                class="radio-check col-lg-4 d-flex {{ $job->apply_on === 'app' ? 'checked' : '' }}"
                                                onclick="RadioChecked('app')">
                                                <input type="radio" {{ $job->apply_on === 'app' ? 'checked' : '' }}
                                                    checked name="apply_on" value="app" id="app-app">
                                                <label for="app-app">
                                                    <h4 class="d-inline-block">{{ __('onn') }}
                                                        {{ config('app.name') }}</h4>
                                                    <p class="tw-mb-0">{{ __('candidate_will_apply_job_using') }}
                                                        {{ config('app.name') }} &
                                                        {{ __('all_application_will_show_on_your_dashboard') }}.</p>
                                                </label>
                                            </div>
                                            <div id="applied_on_custom_url"
                                                class="radio-check col-lg-4 d-flex {{ $job->apply_on === 'custom_url' ? 'checked' : '' }}"
                                                onclick="RadioChecked('custom_url')">
                                                <input type="radio"
                                                    {{ $job->apply_on === 'custom_url' ? 'checked' : '' }} name="apply_on"
                                                    value="custom_url" id="app-custom_url">
                                                <label for="app-custom_url">
                                                    <h4 class="d-inline-block">{{ __('external_platform') }}</h4>
                                                    <p class="tw-mb-0">
                                                        {{ __('candidate_apply_job_on_your_website_all_application_on_your_own_website') }}.
                                                    </p>
                                                </label>
                                            </div>
                                            <div id="applied_on_email"
                                                class="radio-check col-lg-4 d-flex {{ $job->apply_on === 'email' ? 'checked' : '' }}"
                                                onclick="RadioChecked('email')">
                                                <input type="radio" {{ $job->apply_on === 'email' ? 'checked' : '' }}
                                                    name="apply_on" value="email" id="app-email">
                                                <label for="app-email">
                                                    <h4 class="d-inline-block">{{ __('on_your_email') }}</h4>
                                                    <p class="tw-mb-0">
                                                        {{ __('candidate_apply_job_on_your_email_address_and_all_application_in_your_email') }}.
                                                    </p>
                                                </label>
                                            </div>
                                            <!-- apply_on end-->
                                            <div class="col-12 d-none" id="apply_on_custom_url">
                                                <x-forms.label name="website_url" :required="true" />
                                                <div class="fromGroup has-icon2">
                                                    <div class="form-control-icon">
                                                        <input value="{{ $job->apply_url }}" name="apply_url"
                                                            class="form-control @error('apply_url') is-invalid @enderror"
                                                            type="url" placeholder="{{ __('website') }}">
                                                        <div class="icon-badge-2 @error('apply_url') mt-n-11 @enderror">
                                                            <x-svg.link-icon />
                                                        </div>
                                                        @error('apply_url')
                                                            <span class="error invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 d-none" id="apply_on_email">
                                                <x-forms.label name="email_address" :required="true" />
                                                <div class="fromGroup has-icon2">
                                                    <div class="form-control-icon">
                                                        <input value="{{ $job->apply_email }}" name="apply_email"
                                                            class="form-control @error('apply_email') is-invalid @enderror"
                                                            type="email" placeholder="{{ __('email_address') }}">
                                                        <div class="icon-badge-2 @error('apply_email') mt-n-11 @enderror">
                                                            <x-svg.envelope-icon />
                                                        </div>
                                                        @error('apply_email')
                                                            <span class="error invalid-feedback">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="post-job-item rt-mb-15">
                                <button type="submit" class="btn btn-primary rt-mr-10">
                                    <span class="button-content-wrapper ">
                                        <span class="button-icon align-icon-right">
                                            <i class="ph-arrow-right"></i>
                                        </span>
                                        <span class="button-text">
                                            {{ __('post_job') }}
                                        </span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('frontend') }}/assets/css/bootstrap-datepicker.min.css">
    <x-map.leaflet.map_links />
    <x-map.leaflet.autocomplete_links />
    <style>
        .ck-editor__editable_inline {
            min-height: 300px;
        }

        .mymap {
            border-radius: 12px;
        }

        .mt-n-11 {
            margin-top: -11px;
        }
        .custom-checkbox-wrap .main input:checked~.custom-checkbox:after {
            left: 8% !important;
        }
    </style>
    <!-- >=>Mapbox<=< -->
    @include('map::links')
    <!-- >=>Mapbox<=< -->
@endsection

@section('frontend_scripts')
    <script src="{{ asset('frontend/assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('frontend') }}/assets/js/ckeditor.min.js"></script>
    @include('map::set-edit-leafletmap', ['lat' => $job->lat, 'long' => $job->long])
    <script>
        //init datepicker
        $("#date").attr("autocomplete", "off");
        //init datepicker
        $('.datepicker').off('focus').datepicker({
            format: 'dd-mm-yyyy'
        }).on('click',
            function() {
                $(this).datepicker('show');
            }
        );
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#default'))
            .catch(error => {
                console.error(error);
            });
    </script>

    @include('map::set-edit-googlemap', ['job' => $job])
    @include('map::set-edit-mapbox', ['job' => $job])

    <script>
        function RadioChecked(param) {
            var value = param;
            if (value === 'email') {
                $('#applied_on_email').addClass('checked');
                $('#apply_on_custom_url').addClass('d-none');
                $('#apply_on_email').removeClass('d-none');
                $('#applied_on_app').removeClass('checked');
                $('#applied_on_custom_url').removeClass('checked');
            }
            if (value === 'custom_url') {
                $('#applied_on_custom_url').addClass('checked');
                $('#apply_on_email').addClass('d-none');
                $('#apply_on_custom_url').removeClass('d-none');
                $('#applied_on_app').removeClass('checked');
                $('#applied_on_email').removeClass('checked');
            }
            if (value === 'app') {
                $('#applied_on_app').addClass('checked');
                $('#applied_on_email').removeClass('checked');
                $('#applied_on_custom_url').removeClass('checked');
                $('#apply_on_email').addClass('d-none');
                $('#apply_on_custom_url').addClass('d-none');
            }
        }
        $('.radio-check').on('click', function() {
            $('input:radio', this).prop('checked', true);
        });

        if ($('#app-app').is(':checked')) {
            $('#applied_on_app').addClass('checked');
        }
        if ($('#app-custom_url').is(':checked')) {
            $('#apply_on_custom_url').removeClass('d-none');
        }
        if ($('#app-email').is(':checked')) {
            $('#apply_on_email').removeClass('d-none');
        }

        var apply_url = "{!! $errors->first('apply_url') !!}";
        var apply_url1 = "{!! old('apply_email') !!}";
        var apply_email = "{!! $errors->first('apply_email') !!}";
        var apply_email1 = "{!! old('apply_email') !!}";

        if (apply_url) {
            $('#apply_on_custom_url').removeClass('d-none');
        }
        if (apply_url1) {
            $('#apply_on_custom_url').removeClass('d-none');
        }
        if (apply_email) {
            $('#apply_on_email').removeClass('d-none');
        }
        if (apply_email1) {
            $('#apply_on_email').removeClass('d-none');
        }
    </script>
@endsection
