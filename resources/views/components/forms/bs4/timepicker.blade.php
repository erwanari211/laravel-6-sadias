@php
    $label = null;
    if (isset($attributes['label'])) {
        $label = $attributes['label'];
        $attributes['label'] = null;
    }

    $icon = 'fa fa-clock-o';
    if (isset($attributes['icon'])) {
        $icon = $attributes['icon'];
        $attributes['icon'] = null;
    }

    $defaultAttributes = [
        'class' => 'form-control datetimepicker',
        'data-date-format' => 'HH:mm',
    ];

    if ($errors->has($name)) {
        $defaultAttributes['class'] = 'form-control datetimepicker is-invalid';
    }
@endphp

<div class="form-group {{ $errors->has($name) ?  'has-error' : '' }}">
    {{ Form::label($name, $label, ['class' => 'control-label']) }}
    @if ($icon)
        <div class="input-group date">
            <span class="input-group-addon">
                <i class="{{ $icon }}"></i>
            </span>
            {{ Form::text($name, $value, array_merge($defaultAttributes, $attributes)) }}
        </div>
    @else
        {{ Form::text($name, $value, array_merge($defaultAttributes, $attributes)) }}
    @endif

    @if ($errors->has($name))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first($name) }}</strong>
        </span>
    @endif
</div>
