@php
    $label = null;
    if (isset($attributes['label'])) {
        $label = $attributes['label'];
        $attributes['label'] = null;
    }

    $defaultAttributes = [
        'class' => 'btn btn-primary',
        'type' => 'submit',
    ];
@endphp

{!! Form::button($name, array_merge($defaultAttributes, $attributes)); !!}
