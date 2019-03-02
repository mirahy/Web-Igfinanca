<div class="form-group row">
    <label class="{{ $class ?? null }}">
        <div class="col-lg-12">
            <span>{{ $label ?? $imput ?? "ERRO" }}</span>
            {!! Form::select($id_item, $itens, $selected, $classinput) !!}
    </label>
</div>
</div>
