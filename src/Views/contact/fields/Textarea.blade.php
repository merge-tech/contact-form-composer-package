<div class="form-group textarea_field">
    <label for="{{$field->field_name}}">{{$field->label}}</label>

    <textarea
            name='{{$field->field_name}}'
            class="form-control"
            id="{{$field->field_name}}"
            aria-describedby="{{$field->field_name}}Helper"
            rows='7'


            {{ $field->requiredTagAttribute() }}
            {!! $field->placeholderTagAttribute() !!}


    >{{old($field->field_name,$field->default)}}</textarea>
    @include("contact::shared.error",['field'=>$field])

    @if($field->description)
        <small id="{{$field->field_name}}Helper"
               class="form-text text-muted contact_desc">{{$field->description}}</small>
    @endif

</div>


