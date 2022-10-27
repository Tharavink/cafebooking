@component('mail::message')
# Introduction

A table is available in {{ $table->cafe->name }}. Head to {{ config('app.name') }} now to book.

@component('mail::button', ['url' => route('cafe.dashboard')])
Book In Cafe
@endcomponent

If you like to cancel waiting for this cafe, please press the button below.
Please note that this action will cancel ALL your waiting requests for this cafe.

@component('mail::button', ['url' => route('waiting.cancel', $table->cafe->id)])
Cancel Waiting
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
