<div class="form-group text_field">
    <label for="{{$field->field_name}}">{{$field->label}}</label>


    <input
            type="{{ $input_type ?? 'text' }}"
            name='{{$field->field_name}}'
            class="form-control"
            id="{{$field->field_name}}"

            {{ $field->requiredTagAttribute() }}
            {!! $field->placeholderTagAttribute() !!}
            {!! $field->valueTagAttribute() !!}
            aria-describedby="{{$field->field_name}}Helper"
    >

    @include("contact::shared.error",['field'=>$field])

    @if($field->description)
        <small id="{{$field->field_name}}Helper"
               class="form-text text-muted contact_desc">{{$field->description}}</small>
    @endif

</div>


