{{--
  Small reusable component example
  Location: src/resources/views/components/example-button.blade.php
--}}

@props(['label' => 'Button'])

<button {{ $attributes->merge(['class' => 'btn btn-primary']) }}>
  {{ $label }}
</button>
