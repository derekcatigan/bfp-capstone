{{-- resources\views\components\icon.blade.php --}}
@props(['name', 'class' => 'w-6 h-6'])

@php
    $view = 'components.icons.' . $name;
    $path = resource_path("views/components/icons/{$name}.blade.php");
@endphp

@if(file_exists($path))
    <span {{ $attributes->merge(['class' => $class]) }}>
        @include($view)
    </span>
@endif