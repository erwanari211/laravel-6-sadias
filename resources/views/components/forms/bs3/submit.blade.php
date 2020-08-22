@php
    $label = null;
    if (isset($attributes['label'])) {
        $label = $attributes['label'];
        $attributes['label'] = null;
    }

    $defaultAttributes = [
        'class' => 'btn btn-primary'
    ];
@endphp

{!! Form::submit($name, array_merge($defaultAttributes, $attributes)); !!}

