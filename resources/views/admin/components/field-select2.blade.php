@props([
    'name',
    'id' => null,
    'label' => null,
    'options' => [],
    'multiple' => false,
    'placeholder' => 'Choose ...',
    'selected' => [],
])

@php
    $selectedValues = is_array($selected) ? $selected : [$selected];
@endphp

@if ($label)
    <label for="{{ $id ?? $name }}" class="mb-1 fw-bold text-muted">{{ $label }}</label>
@endif
<select name="{{ $name }}{{ $multiple ? '[]' : '' }}" id="{{ $id ?? $name }}"
    class="form-control select2 {{ $multiple ? 'select2-multiple' : '' }}" data-toggle="select2"
    {{ $multiple ? 'multiple="multiple"' : '' }} data-placeholder="{{ $placeholder }}" {{ $attributes }}>
    @if (!$multiple)
        <option value="">{{ $placeholder }}</option>
    @endif
    {{ $slot }}
    @foreach ($options as $value => $text)
        @if (is_array($text))
            <optgroup label="{{ $value }}">
                @foreach ($text as $subValue => $subText)
                    <option value="{{ $subValue }}" {{ in_array($subValue, $selectedValues) ? 'selected' : '' }}>
                        {{ $subText }}</option>
                @endforeach
            </optgroup>
        @else
            <option value="{{ $value }}" {{ in_array($value, $selectedValues) ? 'selected' : '' }}>
                {{ $text }}</option>
        @endif
    @endforeach
</select>
