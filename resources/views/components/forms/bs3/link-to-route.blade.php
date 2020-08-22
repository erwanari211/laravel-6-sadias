@php
    $defaultAttributes = [
        'class' => 'btn btn-default'
    ];
@endphp

{!! link_to_route($routeName, $title, $parameters, array_merge($defaultAttributes, $attributes)) !!}

