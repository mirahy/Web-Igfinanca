<div class="col-lg-12 {{$class ?? null}}">
    <label class ="col-lg-4 control-label">{{$label ?? null}}</label>
        <div class="form-group">
            {!! Form::date($date, \Carbon\Carbon::now(), $attributes) !!}
        </div>
    <span class="help-block"></span>
</div>
