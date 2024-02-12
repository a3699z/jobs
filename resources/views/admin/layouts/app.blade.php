@if ($setting->default_layout)
    @include('admin.layouts.left-nav')
@else
    @include('admin.layouts.top-nav')
@endif
<input type="hidden" value="{{ current_country_code() }}" id="current_country_code">
