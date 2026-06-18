@props(['type' => 'text', 'name', 'id' => null, 'label' => null, 'value' => '', 'placeholder' => ''])

<div class="mb-3">
    @if ($label)
        <label for="{{ $id ?? $name }}" class="form-label">{{ $label }}</label>
    @endif
    <input type="{{ $type }}" class="form-control" name="{{ $name }}" id="{{ $id ?? $name }}"
        value="{{ $value }}" placeholder="{{ $placeholder }}" {{ $attributes }}>
    @error($name)
        <span class="text-danger small">{{ $message }}</span>
    @enderror
</div>
