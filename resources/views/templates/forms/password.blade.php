<div class="form-group">
    <label class ="col-lg-4 control-label">{{$label ?? null}}</label>
        <div class="col-lg-12">
            {!! Form::password($input, $attributes) !!}
        </div>
        <span class="help-block"></span>
</div>
