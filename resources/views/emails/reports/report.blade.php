@component('mail::message')
# Observation Report for {{ $child->name }}

Dear Parent,

Please find attached the finalized observation report.

Thanks,
{{ config('app.name') }}
@endcomponent
