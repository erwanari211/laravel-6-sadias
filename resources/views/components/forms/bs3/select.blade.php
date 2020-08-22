@php
    $label = null;
    if (isset($attributes['label'])) {
        $label = $attributes['label'];
        $attributes['label'] = null;
    }

    $defaultAttributes = [
        'class' => 'form-control',
        'placeholder' => 'Please Select'
    ];
@endphp

<div class="form-group {{ $errors->has($name) ?  'has-error' : '' }}">
    {{ Form::label($name, $label, ['class' => 'control-label']) }}
    {{ Form::select($name, $options, $value, array_merge($defaultAttributes, $attributes)) }}
    @if ($errors->has($name))
        <span class="help-block" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
