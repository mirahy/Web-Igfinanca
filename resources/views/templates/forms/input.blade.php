<div class="form-group row">
    <label class="{{ $class ?? null }}">
        <div class="col-lg-12">
            <span>{{ $label ?? $input ?? "ERRO" }}</span>
            {!! Form::text($input, $value ?? null, $attributes) !!}
    </label>
</div>
</div>
