<div class="form-group row">
    <label class="{{ $class ?? null }}">
        <div class="col-lg-12">
            <span>{{ $label ?? $input ?? "ERRO" }}</span>
            {!! Form::date($input, \Carbon\Carbon::now(), $attributes) !!}
    </label>
</div>
</div>
