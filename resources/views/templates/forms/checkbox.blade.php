<div class="col-lg-12 {{$class ?? null}}">
    <label class ="col-lg-4 control-label">{{$label ?? null}}</label>
        <div class="form-group">
        {!! Form::checkbox($name ?? null, $value, $checked, $attributes ?? null) !!}
        </div>
    <span class="help-block"></span>
</div>