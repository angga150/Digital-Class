@props(['name', 'label', 'type' => 'text'])
<div>
    <label for="{{ $name }}" class="mb-2 block text-sm font-semibold">{{ $label }}</label>
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" {{ $attributes->class(['field']) }} @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror>
    @error($name)<p id="{{ $name }}-error" class="mt-2 text-xs text-red-700">{{ $message }}</p>@enderror
</div>
