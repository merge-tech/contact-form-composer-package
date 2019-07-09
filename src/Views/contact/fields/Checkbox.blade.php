<div class="form-group checkbox_field">
    <label for="{{$field->field_name}}">{{$field->label}}</label>


    <input type="checkbox"

           name='{{$field->field_name}}'
           class="form-control"
           id="{{$field->field_name}}"
           aria-describedby="{{$field->field_name}}Helper"
           placeholder="{{$field->placeholder}}"

           value="1"

            {{ $field->requiredTagAttribute() }}


            {{ old($field->field_name,$field->default) ? " checked='checked' " : ""}}
    >
    @include("contact::shared.error",['field'=>$field])

    @if($field->description)
        <small id="{{$field->field_name}}Helper" class="form-text text-muted">{{$field->description}}</small>
    @endif

</div>


