<div class="col-lg-12">
    <label class ="col-lg-4 control-label">{{$label ?? null}}</label>
    <div class="form-group ">    
         {!! Form::email($input, $value ?? null, $attributes) !!}
    </div>
    <span class="help-block"></span>
</div>
