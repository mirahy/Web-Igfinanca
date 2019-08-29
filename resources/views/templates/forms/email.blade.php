<div class="form-group "> 
    <label class ="col-lg-4 control-label">{{$label ?? null}}</label>   
    <div class="col-lg-12">
         {!! Form::email($input, $value ?? null, $attributes) !!}
    </div>
    <span class="help-block"></span>
</div>
