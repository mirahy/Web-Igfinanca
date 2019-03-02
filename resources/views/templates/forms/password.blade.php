<div class="form-group row">
    <label class="{{ $class ?? null }}">
        <div class="col-lg-12">
            <span>{{ $label ?? $input ?? "ERRO" }}</span>
            {!! Form::password($input, $attributes) !!}
    </label>
</div>
</div>
