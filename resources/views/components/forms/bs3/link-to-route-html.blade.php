@php
    $defaultAttributes = [
        'class' => 'btn btn-default'
    ];
@endphp

{!! link_to_route_html($routeName, $title, $parameters, array_merge($defaultAttributes, $attributes)) !!}

