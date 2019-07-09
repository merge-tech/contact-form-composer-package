{{-- You have access to variables including: $submitted_data (array of the submitted POST data), $contact_page_details (some additioan detail, from the config file, about this contact page. Also includes $fields within this) --}}
@component('mail::message')
# Contact Form Response (Form: {{$contact_form->human_readable_form_name}})

A new contact form response was submitted at {{ Carbon\Carbon::now() }}.

__________

@foreach($fields as $field)

# {{$field->label}} <small style='color: #bbb'>{{$field->field_name}}</small>

> {!! $field->forEmailOutput(@$submitted_data[$field->field_name]) ?? "[no submitted data]"  !!}

@endforeach

__________

Thanks,<br>
{{ config('app.name') }}

@endcomponent
