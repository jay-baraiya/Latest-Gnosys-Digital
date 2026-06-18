@props([
    'name',
    'id' => null,
    'label' => null,
    'prepend' => null,
    'append' => null,
    'type' => 'text',
    'placeholder' => '',
    'value' => '',
    'is_required' => true,
])

<div class="mb-3">
    @if ($label)
        <label class="form-label" for="{{ $id ?? $name }}">{{ $label }} @if ($is_required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    <div class="input-group mb-1">
        @if ($prepend)
            <span class="input-group-text">{!! $prepend !!}</span>
        @elseif(isset($prependSlot))
            {{ $prependSlot }}
        @endif

        <input type="{{ $type }}" class="form-control" name="{{ $name }}" id="{{ $id ?? $name }}"
            placeholder="{{ $placeholder }}" value="{{ $value }}" {{ $attributes }}>

        @if ($append)
            <span class="input-group-text">{!! $append !!}</span>
        @elseif(isset($appendSlot))
            {{ $appendSlot }}
        @endif
    </div>
    @error($name)
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>
