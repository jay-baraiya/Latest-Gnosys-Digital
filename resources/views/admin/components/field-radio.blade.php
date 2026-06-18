@props(['name', 'id' => null, 'label' => null, 'value' => '', 'checked' => false, 'inline' => false])

<div class="form-check {{ $inline ? 'form-check-inline' : '' }}">
    <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $id ?? $value }}"
        value="{{ $value }}" @if ($checked) checked @endif {{ $attributes }}>
    <label class="form-check-label" for="{{ $id ?? $value }}">
        {{ $label ?? $slot }}
    </label>
</div>
