@props(['name', 'id' => null, 'label' => null, 'rows' => 3, 'placeholder' => ''])

<div class="mb-3">
    @if ($label)
        <label for="{{ $id ?? $name }}" class="form-label">{{ $label }}</label>
    @endif
    <textarea class="form-control" name="{{ $name }}" id="{{ $id ?? $name }}" rows="{{ $rows }}"
        placeholder="{{ $placeholder }}" {{ $attributes }}>{{ $slot }}</textarea>
</div>
