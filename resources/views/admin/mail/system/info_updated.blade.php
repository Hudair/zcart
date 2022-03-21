@component('mail::message')
#{{ trans('notifications.system_info_updated.greeting', ['user' => $system->superAdmin()->getName()]) }}

{{ trans('notifications.system_info_updated.message', ['marketplace' => get_platform_title()]) }}
<br/>

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ trans('notifications.system_info_updated.button_text') }}
@endcomponent

{{ trans('messages.thanks') }},<br>
{{ get_platform_title() }}
@endcomponent
