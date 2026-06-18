@props([
    'name',
    'id' => null,
    'label' => null,
    'value' => '1',
    'checked' => false,
    'inline' => false,
    'isSwitch' => false,
])

<div class="form-check {{ $inline ? 'form-check-inline' : '' }} {{ $isSwitch ? 'form-switch' : '' }}">
    <input class="form-check-input" type="checkbox" {{ $isSwitch ? 'role="switch"' : '' }} name="{{ $name }}"
        value="{{ $value }}" id="{{ $id ?? $name }}" @if ($checked) checked @endif
        {{ $attributes }}>
    <label class="form-check-label" for="{{ $id ?? $name }}">
        {{ $label ?? $slot }}
    </label>
</div>
