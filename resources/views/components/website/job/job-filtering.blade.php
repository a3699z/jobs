@props(['countries', 'categories', 'jobRoles', 'min_salary', 'max_salary', 'experiences', 'educations', 'jobTypes'])

<div class="breadcrumbs style-two">
    <div class="container">
        <div class="row align-items-center ">
            <div class="col-12 position-relative ">
                <div class="breadcrumb-menu">
                    <h6 class="f-size-18 m-0">{{ __('find_job') }}</h6>
                    <ul>
                        <li><a href="{{ route('website.home') }}">{{ __('home') }}</a></li>
                        <li>/</li>
                        <li>{{ __('find_job') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <div class="container">
        <div class="row tw-filter-box tw-my-6 tw-mx-1.5 sm:tw-mx-0">
            <div class="col-lg-5 tw-p-3 search-col">
                <div class="search-col-4 fromGroup position-relative">
                    <input id="search_jobs" name="keyword" type="text" placeholder="{{ __('job_title_keyword') }}"
                        value="{{ request('keyword') }}" autocomplete="off" class="tw-border-0 tw-pl-12">
                    <div class="tw-absolute tw-top-1/2 -tw-translate-y-1/2 tw-left-3">
                        <x-svg.search-icon />
                    </div>
                    <span id="autocomplete_job_results_job_page"></span>
                </div>
            </div>
            <input type="hidden" name="lat" id="lat" class="leaf_lat" value="{{ request('lat') }}">
            <input type="hidden" name="long" id="long" class="leaf_lon" value="{{ request('long') }}">
            @php
                $oldLocation = request('location');
                $map = setting('default_map');
            @endphp

            <div class="col-lg-7 tw-p-3">
                <div class="tw-flex tw-flex-wrap md:tw-flex-nowrap tw-gap-3">
                    <div class="tw-w-full tw-relative fromGroup">
                        @if ($map == 'map-box')
                            <input type="hidden" name="location" id="insertlocation" value="{{ request('location') }}">
                            <div class="search-col fromGroup has-icon banner-select no-border">
                                <span id="geocoder"></span>
                                <div class="icon-badge">
                                    <x-svg.location-icon width="24" height="24"
                                        stroke="{{ $setting->frontend_primary_color }}" />
                                </div>
                            </div>
                        @elseif ($map == 'google-map')
                            <input type="text" id="searchInput" placeholder="{{ __('enter_location') }}"
                                name="location" value="{{ request('location') }}" class="tw-border-0 tw-pl-12" />
                            <div id="google-map" class="d-none"></div>
                        @else
                            <input name="long" class="leaf_lon" type="hidden" value="{{ request('lat') }}">
                            <input name="lat" class="leaf_lat" type="hidden" value="{{ request('long') }}">
                            <input type="text" id="leaflet_search" placeholder="{{ __('enter_location') }}"
                                name="location" value="{{ request('location') }}" class="tw-border-0 tw-pl-12"
                                autocomplete="off" />
                        @endif

                        <div class="tw-absolute tw-top-1/2 -tw-translate-y-1/2 tw-left-3">
                            <x-svg.location-icon width="24" height="24"
                                stroke="{{ $setting->frontend_primary_color }}" />
                        </div>
                    </div>
                    <div>
                        <button type="button"
                            class="btn tw-inline-flex gap-3 tw-items-center hover:tw-bg-[#F1F2F4] tw-bg-[#F1F2F4] hover:tw-text-[#18191C] tw-text-[#18191C] tw-border-0"
                            data-bs-toggle="modal" data-bs-target="#filtersModal">
                            <span class="">
                                <x-svg.filters-icon />
                            </span>
                            <span>{{ __('filter') }}</span>
                        </button>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary tw-inline-block">
                            {{ __('find_job') }}
                        </button>
                    </div>
                </div>
                <span id="autocomplete_job_results"></span>
            </div>
        </div>
    </div>
</div>
<x-website.modal.filters-modal :job-types="$jobTypes" :categories="$categories" :max-salary="$maxSalary" />

@push('frontend_links')
    <link rel="stylesheet" href="{{ asset('frontend') }}/plugins/nouislider/nouislider.min.css">
    <link rel="stylesheet" href="{{ asset('backend') }}/plugins/fontawesome-free/css/all.min.css">
    @if ($map == 'leaflet')
        <x-map.leaflet.autocomplete_links />
    @endif
    <link rel="stylesheet" href="{{ asset('frontend/plugins/mapbox/mapbox-gl.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/plugins/mapbox/style.css') }}">
    <style>
        span.select2-container--default .select2-selection--single {
            border: none !important;
        }

        span.select2-selection.select2-selection--single {
            outline: none;
        }

        .noUi-connect {
            background: #0066ff;
        }

        #priceRangeSlider {
            height: 8px;
        }

        .noUi-horizontal .noUi-handle {
            height: 16px;
            width: 16px;
            top: -5px;
            border-radius: 50%;
            background: #0066ff;
            border: 2px solid white;
        }

        .noUi-touch-area {
            background: #0066ff;
            border-radius: 50%;
        }

        .mapboxgl-ctrl {
            z-index: 2 !important;
        }

        .noUi-handle:after,
        .noUi-handle:before {
            display: none;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-search@2.3.7/dist/leaflet-search.src.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.2.0/dist/leaflet.css" />
@endpush



{{-- <div id="map" style="height: 200px"></div> --}}

@push('frontend_scripts')
    <script src="{{ asset('frontend/plugins/nouislider/nouislider.min.js') }}"></script>
    <script src="{{ asset('frontend/plugins/nouislider/wNumb.min.js') }}"></script>
    <script>
        // autocomplete
        var path = "{{ route('website.job.autocomplete') }}";

        $('#search_jobs').keyup(function(e) {
            var keyword = $(this).val();

            if (keyword != '') {
                $.ajax({
                    url: path,
                    type: 'GET',
                    dataType: "json",
                    data: {
                        search: keyword
                    },
                    success: function(data) {
                        $('#autocomplete_job_results_job_page').fadeIn();
                        $('#autocomplete_job_results_job_page').html(data);
                    }
                });
            } else {
                $('#autocomplete_job_results_job_page').fadeOut();
            }
        });
    </script>
    @if ($map == 'leaflet')
        <x-map.leaflet.autocomplete_scripts />
    @elseif ($map == 'map-box')
        <script src="{{ asset('frontend/plugins/mapbox/mapbox-gl.js') }}"></script>
        <script src="{{ asset('frontend/plugins/mapbox/mapbox-gl-geocoder.min.js') }}"></script>

        <script>
            var country_code = '{{ current_country_code() }}'

            mapboxgl.accessToken = "{{ $setting->map_box_key }}";
            const geocoder = new MapboxGeocoder({
                accessToken: mapboxgl.accessToken,
                types: 'country,region,place,postcode,locality,neighborhood',
                countries: country_code ? country_code : ''
            });
            geocoder.addTo('#geocoder');
            // Add geocoder result to container.
            geocoder.on('result', (e) => {
                // console.log(e.result.center);
                var full_address = e.result.place_name;
                var words = full_address.split(",");
                var country = words.pop();
                var place = words.pop();
                const text = place + ',' + country;

                $('#insertlocation').val(full_address);
                $('#lat').val(e.result.center[1]);
                $('#long').val(e.result.center[0]);
            });
        </script>
        <script>
            $('.mapboxgl-ctrl-geocoder--icon').hide();
            $('.mapboxgl-ctrl-geocoder--input').attr("placeholder", "Location");
            var oldLocation = "{!! $oldLocation !!}";
            if (oldLocation) {
                $('.mapboxgl-ctrl-geocoder--input').val(oldLocation);
            }

            $(".mapboxgl-ctrl-geocoder--input").change(function() {
                if ($(this).val().length > 0) {
                    // ('#insertlocation').val($(this).val());
                } else {
                    $('#insertlocation').val($(this).val());
                    $('#lat').val('');
                    $('#long').val('');
                }
            })
            $("#searchInput").change(function() {
                if ($(this).val().length > 0) {
                    // ('#insertlocation').val($(this).val());
                } else {
                    $('#insertlocation').val($(this).val());
                    $('#lat').val('');
                    $('#long').val('');
                }
            })
        </script>
    @elseif ($map == 'google-map')
        <!-- ============== gooogle map ========== -->
        <script>
            function initMap() {
                var token = "{{ $setting->google_map_key }}";
                var oldlat = {{ Session::has('location') ? Session::get('location')['lat'] : setting('default_lat') }};
                var oldlng = {{ Session::has('location') ? Session::get('location')['lng'] : setting('default_long') }};
                const map = new google.maps.Map(document.getElementById("google-map"), {
                    zoom: 7,
                    center: {
                        lat: oldlat,
                        lng: oldlng
                    },
                });
                // Search
                var input = document.getElementById('searchInput');
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

                let country_code = '{{ current_country_code() }}';
                if (country_code) {
                    var options = {
                        componentRestrictions: {
                            country: country_code
                        }
                    };
                    var autocomplete = new google.maps.places.Autocomplete(input, options);
                } else {
                    var autocomplete = new google.maps.places.Autocomplete(input);
                }

                autocomplete.bindTo('bounds', map);
                var infowindow = new google.maps.InfoWindow();
                var marker = new google.maps.Marker({
                    map: map,
                    anchorPoint: new google.maps.Point(0, -29)
                });
                autocomplete.addListener('place_changed', function() {
                    infowindow.close();
                    marker.setVisible(false);
                    var place = autocomplete.getPlace();
                    const total = place.address_components.length;
                    let amount = '';
                    if (total > 1) {
                        amount = total - 2;
                    }
                    const result = place.address_components.slice(amount);
                    let country = '';
                    let region = '';
                    for (let index = 0; index < result.length; index++) {
                        const element = result[index];
                        if (element.types[0] == 'country') {
                            country = element.long_name;
                        }
                        if (element.types[0] == 'administrative_area_level_1') {
                            const str = element.long_name;
                            const first = str.split(',').shift()
                            region = first;
                        }
                    }
                    const text = country;
                    $('#insertlocation').val(text);
                    $('#lat').val(place.geometry.location.lat());
                    $('#long').val(place.geometry.location.lng());
                    if (place.geometry.viewport) {
                        map.fitBounds(place.geometry.viewport);
                    } else {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                    }
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
    @endif
@endpush
