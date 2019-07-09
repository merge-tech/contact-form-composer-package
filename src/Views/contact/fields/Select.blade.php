<div class="form-group text_field">
    <label for="{{$field->field_name}}">{{$field->label}}</label>


    <select
            name='{{$field->field_name}}'
            class="form-control"
            id="{{$field->field_name}}"

            {{ $field->requiredTagAttribute() }}
            aria-describedby="{{$field->field_name}}Helper"
    >
        @foreach($field->getOptions() as $option_name=>$val)
            <option value='{{$option_name}}' @if(old($field->field_name) == $option_name) selected @endif>{{$val}}</option>
        @endforeach

        </select>

    @include("contact::shared.error",['field'=>$field])

    @if($field->description)
        <small id="{{$field->field_name}}Helper"
               class="form-text text-muted contact_desc">{{$field->description}}</small>
    @endif

</div>


