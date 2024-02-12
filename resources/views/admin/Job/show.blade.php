@extends('admin.layouts.app')
@section('title')
    {{ __('job_list') }}
@endsection
@section('content')
    @php
    $userr = auth()->user();
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title line-height-36">{{ $job->title }}</h3>
                        <a href="{{ route('job.index') }}"
                            class="btn bg-primary float-right d-flex align-items-center justify-content-center"><i
                                class="fas fa-arrow-left mr-1"></i>{{ __('back') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="pt-3 pb-4">
                            <div class="row form-group">
                                <div class="col-12">
                                    <label for="title">
                                        {{ __('title') }}
                                    </label>
                                    <input id="title" class="form-control" value="{{ $job->title }}" disabled>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4">
                                    <label for="company">
                                        {{ __('company') }}
                                    </label>
                                    <select class="form-control" id="company" disabled>
                                        <option> {{ $job->company->user->name }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="category">
                                        {{ __('category') }}
                                    </label>
                                    <select class="form-control" id="category" disabled>
                                        <option>{{ $job->category->name }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="job_role">
                                        {{ __('job_role') }}
                                    </label>
                                    <select class="form-control" id="job_role" disabled>
                                        <option>{{ $job->role->name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="experience">
                                        {{ __('experience') }}
                                    </label>
                                    <select class="form-control" id="experience" disabled>
                                        <option>{{ $job->experience->name }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="gender">
                                        {{ __('gender') }}
                                    </label>
                                    <select name="gender" class="form-control @error('gender') is-invalid @enderror"
                                        id="gender" disabled>
                                        <option> {{ __($job->gender) }}</option>
                                    </select>
                                    @error('gender')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="education">
                                        {{ __('education') }}
                                    </label>
                                    <input id="education" class="form-control" value="{{ $job->education->name }}"
                                        disabled>
                                </div>
                                <div class="col-6">
                                    <label for="job_type">
                                        {{ __('job_type') }}
                                    </label>
                                    <input id="job_type" class="form-control"
                                        value="{{ $job->job_type ? $job->job_type->name : '' }}" disabled>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-6">
                                    <label for="tags">
                                        {{ __('tags') }}
                                    </label>
                                    <input id="tags" class="form-control" value="{{ $job->tags }}" disabled>
                                </div>
                                <div class="col-6">
                                    <label for="deadline">
                                        {{ __('deadline') }}
                                    </label>
                                    <input id="deadline" class="form-control"
                                        value="{{ date('Y-m-d', strtotime($job->deadline)) }}" disabled>
                                </div>
                            </div>
                            <div class="row form-group">
                                <div class="col-md-4 col-sm-3">
                                    <label for="min_salary">
                                        {{ __('min_salary') }}
                                    </label>
                                    <input id="min_salary" class="form-control" value="{{ $job->min_salary }}" disabled>
                                </div>
                                <div class="col-md-4 col-sm-3">
                                    <label for="max_salary">
                                        {{ __('max_salary') }}
                                    </label>
                                    <input id="max_salary" class="form-control" value="{{ $job->max_salary }}" disabled>

                                </div>
                                <div class="col-md-4 col-sm-3">
                                    <label for="salary_type">
                                        {{ __('salary_type') }}
                                    </label>
                                    <input id="salary_type" class="form-control"
                                        value="{{ __($job->salary_type->name) }}" disabled>
                                </div>
                            </div>
                            <div class="row p-4">
                                <div class="col-md-3 form-check">
                                    <div class="icheck-success d-inline">
                                        <input value="1" name="featured" type="checkbox" class="form-check-input"
                                            id="featured" @if ($job->featured) checked @endif disabled>
                                        <label class="form-check-label mr-5" for="featured">
                                            {{ __('featured') }}
                                            @if ($job->featured && $job->featured_until && $setting->featured_job_days > 0 && isFuture($job->featured_until))
                                                ({{ today()->diffInDays($job->featured_until) }} {{ __('days_remaining') }})
                                            @endif
                                        </label>
                                    </div>
                                    @error('featured')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3 form-check">
                                    <div class="icheck-success d-inline">
                                        <input value="1" name="highlight" type="checkbox" class="form-check-input"
                                            id="highlight" @if ($job->highlight) checked @endif disabled>
                                        <label class="form-check-label mr-5" for="highlight">
                                            {{ __('highlight') }}
                                            @if ($job->highlight && $job->highlight_until && $setting->highlight_job_days > 0 && isFuture($job->highlight_until))
                                                ({{ today()->diffInDays($job->highlight_until) }} {{ __('days_remaining') }})
                                            @endif
                                        </label>
                                    </div>
                                    @error('highlight')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ __($message) }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-3 form-check">
                                    <div class="icheck-success d-inline">
                                        <input value="1" name="is_remote" type="checkbox" class="form-check-input"
                                            id="is_remote" @if ($job->is_remote) checked @endif disabled>
                                        <label class="form-check-label mr-5" for="is_remote">{{ __('remote') }}</label>
                                    </div>
                                    @error('is_remote')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="description">{{ __('description') }}</label>
                                    <p>
                                        {!! $job->description !!}
                                    </p>
                                </div>
                            </div>
                            {{-- Location --}}
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="description">{{ __('location') }}</label>
                                    <div class="p-half rounded">
                                        <x-website.map.map-warning/>
                                        @php
                                            $map = setting('default_map');
                                        @endphp
                                        @if ($map == 'map-box')
                                            <div class="map mymap" id='map-box'></div>
                                        @elseif ($map == 'google-map')
                                            <div class="map mymap" id="google-map"></div>
                                        @else
                                            <div id="leaflet-map"></div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('backend') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('backend') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style>
        .ck-editor__editable_inline {
            min-height: 400px;
        }

        .select2-results__option[aria-selected=true] {
            display: none;
        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            color: #fff;
            border: 1px solid #fff;
            background: #007bff;
            border-radius: 30px;
        }

        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
        }
    </style>
     <!-- >=>Leaflet Map<=< -->
     <x-map.leaflet.map_links/>

    <!-- >=>Mapbox<=< -->
    @include('map::links')
    <!-- >=>Mapbox<=< -->
@endsection

@section('script')
    <script src="{{ asset('backend') }}/plugins/select2/js/select2.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#tags').select2({
                theme: 'bootstrap4',
                tags: true,
                tokenSeparators: [',', ' ']
            });
        });

        ClassicEditor
            .create(document.querySelector('#description'))
            .catch(error => {
                console.error(error);
            });
    </script>
    {{-- Leaflet  --}}
    <x-map.leaflet.map_scripts/>
    <script>
        var oldlat = {!! $job->lat ? $job->lat : setting('default_lat') !!};
        var oldlng = {!! $job->long ? $job->long : setting('default_long') !!};

        // Map preview
        var element = document.getElementById('leaflet-map');

       // Height has to be set. You can do this in CSS too.
       element.style = 'height:300px;';

       // Create Leaflet map on map element.
       var leaflet_map = L.map(element);

       // Add OSM tile layer to the Leaflet map.
       L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
           attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
       }).addTo(leaflet_map);

       // Target's GPS coordinates.
       var target = L.latLng(oldlat, oldlng);

       // Set map's center to target with zoom 14.
       const zoom = 14;
       leaflet_map.setView(target, zoom);

       // Place a marker on the same location.
       L.marker(target).addTo(leaflet_map);

   </script>
    <!-- >=>Mapbox<=< -->
    @include('map::scripts')
    <!-- >=>Mapbox<=< -->
    <!-- ================ mapbox map ============== -->
    <x-website.map.map-box-check/>

    <script>
        mapboxgl.accessToken = "{{ $setting->map_box_key }}";
        const coordinates = document.getElementById('coordinates');

        var oldlat = {!! $job->lat ? $job->lat : setting('default_lat') !!};
        var oldlng = {!! $job->long ? $job->long : setting('default_long') !!};

        const map = new mapboxgl.Map({
            container: 'map-box',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [oldlng, oldlat],
            zoom: 6
        });
        var marker = new mapboxgl.Marker({
                draggable: false
            }).setLngLat([oldlng, oldlat])
            .addTo(map);

        function onDragEnd() {
            const lngLat = marker.getLngLat();
            let lat = lngLat.lat;
            let lng = lngLat.lng;
            $('#lat').val(lat);
            $('#lng').val(lng);
            document.getElementById('form').submit();
        }

        function add_marker(event) {
            var coordinates = event.lngLat;
            marker.setLngLat(coordinates).addTo(map);

        }
        // zoom in and out 
        <x-mapbox-zoom-control />
    </script>
    <script>
        $('.mapboxgl-ctrl-logo').addClass('d-none');
        $('.mapboxgl-ctrl-attrib-inner').addClass('d-none');
    </script>
    <!-- ================ mapbox map ============== -->
    <!-- ================ google map ============== -->
    <x-website.map.google-map-check/>
    <script>
        function initMap() {
            var token = "{{ $setting->google_map_key }}";

            var oldlat = {!! $job->lat ? $job->lat : setting('default_lat') !!};
            var oldlng = {!! $job->long ? $job->long : setting('default_long') !!};

            const map = new google.maps.Map(document.getElementById("google-map"), {
                zoom: 7,
                center: {
                    lat: oldlat,
                    lng: oldlng
                },
            });

            const image =
                "https://gisgeography.com/wp-content/uploads/2018/01/map-marker-3-116x200.png";
            const beachMarker = new google.maps.Marker({

                draggable: false,
                position: {
                    lat: oldlat,
                    lng: oldlng
                },
                map,
                // icon: image
            });
        }
        window.initMap = initMap;
    </script>
    <script>
        @php
            $link1 = 'https://maps.googleapis.com/maps/api/js?key=';
            $link2 = $setting->google_map_key;
            $Link3 = '&callback=initMap&libraries=places,geometry';
            $scr = $link1 . $link2 . $Link3;
        @endphp;
    </script>
    <script src="{{ $scr }}" async defer></script>
    <!-- ================ google map ============== -->
@endsection
