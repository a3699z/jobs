@component('mail::message')
# {{ __('congratulations') }}! {{ __('your_email_configuration_is_successfully_working') }}

 {{ __('thanks') }}<br>
{{ config('app.name') }}
@endcomponent
