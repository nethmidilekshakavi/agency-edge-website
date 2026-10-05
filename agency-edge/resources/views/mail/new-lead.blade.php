<x-mail::message>
# New enquiry from the website

**Name:** {{ $lead->name }}
@if($lead->company)
**Company:** {{ $lead->company }}
@endif
**Email / phone:** {{ $lead->contact }}
@if($lead->goal)
**Wants to:** {{ $lead->goal }}
@endif

@if($lead->message)
<x-mail::panel>
{{ $lead->message }}
</x-mail::panel>
@endif

<x-mail::button :url="url('/admin/leads')">
Open in admin
</x-mail::button>

Sent from {{ $lead->source_page ?: url('/') }}
</x-mail::message>
