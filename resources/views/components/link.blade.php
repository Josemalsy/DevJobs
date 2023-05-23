@php
  $classes = "underline text-xs text-gray-500 hover:text-gray-900"
@endphp

<div>
  <a {{$attributes->merge(['class' => $classes])}}>
    {{ $slot}}
  </a>
</div>