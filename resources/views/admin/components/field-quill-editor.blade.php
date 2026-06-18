@props(['id' => 'editor', 'height' => '300px', 'theme' => 'snow', 'label' => null])

@if ($label)
    <label class="form-label">{{ $label }}</label>
@endif
<div id="{{ $id }}" style="height: {{ $height }};" class="quill-editor-{{ $theme }}"
    {{ $attributes }}>
    {{ $slot }}
</div>

{{-- 
Add this to your page scripts:
<script>
    var quill = new Quill('#{{ $id }}', {
        theme: '{{ $theme }}'
    });
</script>
--}}
