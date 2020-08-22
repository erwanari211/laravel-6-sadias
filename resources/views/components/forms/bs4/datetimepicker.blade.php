@php
    $label = null;
    if (isset($attributes['label'])) {
        $label = $attributes['label'];
        $attributes['label'] = null;
    }

    $icon = 'far fa-calendar-alt';
    if (isset($attributes['icon'])) {
        $icon = $attributes['icon'];
        $attributes['icon'] = null;
    }

    $iconPosition = 'after';
    if (isset($attributes['icon-position'])) {
        $iconPosition = $attributes['icon-position'];
        $attributes['icon-position'] = null;
    }

    $randomId = str_random(8);

    $defaultAttributes = [
        'class' => 'form-control datetimepicker datetimepicker-input',
        'data-target' => '#'.$name,
        'data-toggle' => 'datetimepicker',
    ];


    if ($errors->has($name)) {
        $defaultAttributes['class'] = 'form-control datetimepicker datetimepicker-input is-invalid';
    }
@endphp

<div class="form-group {{ $errors->has($name) ?  'has-error' : '' }}">
    {{ Form::label($name, $label, ['class' => 'control-label']) }}
    @if ($icon)
        <div class="input-group date">
            @if ($iconPosition == 'before')
                <div
                    class="input-group-prepend"
                    data-target="#{{ $name }}"
                    data-toggle="datetimepicker"
                >
                    <span class="input-group-text">
                        <i class="{{ $icon }}"></i>
                    </span>
                </div>
            @endif

            {{ Form::text($name, $value, array_merge($defaultAttributes, $attributes)) }}

            @if ($iconPosition == 'after')
                <div
                    class="input-group-append"
                    data-target="#{{ $name }}"
                    data-toggle="datetimepicker"
                >
                    <span class="input-group-text">
                        <i class="{{ $icon }}"></i>
                    </span>
                </div>
            @endif

            @if ($errors->has($name))
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first($name) }}</strong>
                </div>
            @endif

        </div>
    @else
        {{ Form::text($name, $value, array_merge($defaultAttributes, $attributes)) }}
        @if ($errors->has($name))
            <div class="invalid-feedback" role="alert">
                <strong>{{ $errors->first($name) }}</strong>
            </div>
        @endif
    @endif

</div>
