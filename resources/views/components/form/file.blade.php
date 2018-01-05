<div class="form-group">
    @php $errName = MyHelper::replaceBrackets($name) @endphp
    @if($label)
        {{ Form::label($name, $label, ['class' => 'control-label']) }}
    @endif
     @php $attrs = array_merge(['class' => 'form-control-file'], $attributes) @endphp
     @if($errors->has($errName))
        @php $attrs['class'] .= ' is-invalid' @endphp
     @endif
    {{ Form::file($name, $attrs) }}
    @if($errors->has($errName))
        <div class="invalid-feedback">{{ $errors->first($errName) }}</div>
    @endif
</div>
