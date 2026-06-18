@props(['name', 'id' => null, 'label' => null, 'options' => [], 'placeholder' => 'This is a placeholder'])

@if ($label)
    <label for="{{ $id ?? $name }}" class="form-label text-muted">{{ $label }}</label>
@endif
<select class="form-control" data-choices name="{{ $name }}" id="{{ $id ?? $name }}"
    data-placeholder="{{ $placeholder }}" {{ $attributes }}>
    <option value="">{{ $placeholder }}</option>
    {{ $slot }}
    @foreach ($options as $value => $text)
        @if (is_array($text))
            <optgroup label="{{ $value }}">
                @foreach ($text as $subValue => $subText)
                    <option value="{{ $subValue }}">{{ $subText }}</option>
                @endforeach
            </optgroup>
        @else
            <option value="{{ $value }}">{{ $text }}</option>
        @endif
    @endforeach
</select>
