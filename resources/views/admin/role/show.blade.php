<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
        
            
            <input disabled type="hidden" name="id" id="id" value="{{ isset($role->id) ? encrypt($role->id) : '' }}">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input disabled type="text" class="form-control" name="name" id="name" placeholder="Name"
                                value="{{ old('name', $role->name ?? '') }}">
                        </div>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea disabled class="form-control" name="description" id="description" rows="3" placeholder="Description">{{ old('description', $role->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex gap-3 mb-1">
                    <div class="form-check">
                        <input disabled class="form-check-input" type="radio" name="status" id="status-active" value="1"
                            @if (old('status', isset($role) ? $role->status : 1) == 1) checked @endif>
                        <label class="form-check-label" for="status-active">Active</label>
                    </div>
                    <div class="form-check">
                        <input disabled class="form-check-input" type="radio" name="status" id="status-inactive"
                            value="0" @if (old('status', isset($role) ? $role->status : 1) == 0) checked @endif>
                        <label class="form-check-label" for="status-inactive">Inactive</label>
                    </div>
                </div>
                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <hr>
            <div class="row mt-4 mb-2">
                <div class="col-md-12 mb-3">
                    <h5 class="fw-bold mb-1">Permissions</h5>
                </div>

                @if (!empty($permissions))
                    @foreach ($permissions as $module => $permission)
                        @php
                            $pTotal = count($permission);
                            $rpTotal = 0;
                            if (!empty($rolePermissions)) {
                                $rpMatch = array_intersect($permission->pluck('id')->toArray(), $rolePermissions);
                                $rpTotal = count($rpMatch);
                            }
                        @endphp
                        <div class="col-md-3">
                            <div class="card border shadow-none mb-3 permission-card">
                                <div class="card-header bg-light border-bottom py-2">
                                    <div class="form-check mb-0">
                                        <input disabled class="form-check-input module-checkbox" type="checkbox"
                                            id="{{ strtolower($module) }}-all"
                                            {{ $pTotal == $rpTotal ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold mt-1 text-uppercase"
                                            for="{{ strtolower($module) }}-all"
                                            style="font-size: 0.85rem; letter-spacing: 0.5px;">
                                            {{ $module }}
                                        </label>
                                    </div>
                                </div>

                                <div class="card-body p-3">
                                    <div class="d-flex flex-column gap-2">
                                        @if (!empty($permission))
                                            @foreach ($permission as $perm)
                                                <div class="form-check">
                                                    <input disabled
                                                        class="form-check-input permission-checkbox {{ strtolower($module) }}"
                                                        type="checkbox" name="permissions[]"
                                                        value="{{ $perm->id }}"
                                                        id="{{ $perm->slug }}-{{ $perm->id }}"
                                                        {{ in_array($perm->id, $rolePermissions ?? []) ? 'checked' : '' }}>
                                                    <label class="form-check-label"
                                                        for="{{ $perm->slug }}-{{ $perm->id }}">{{ $perm->name }}</label>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
                
            </div>
        
    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {

                $(document).on('click', '.module-checkbox', function() {
                    let module = $(this).attr('id').replace('-all', '');
                    $('.' + module).prop('checked', $(this).prop('checked'));
                });

                $('.permission-card').each(function(i, e) {
                    var total_checkbox = $(e).find('.permission-checkbox').length;
                    var checked_checkbox = $(e).find('.permission-checkbox:checked').length;
                    if (total_checkbox == checked_checkbox) {
                        $(e).find('.module-checkbox').prop('checked', true);
                    }
                });

                $(document).on('click', '.permission-checkbox', function() {
                    let total_checkbox = $(this).closest('.permission-card').find('.permission-checkbox')
                        .length;

                    let checked_checkbox = $(this).closest('.permission-card').find(
                        '.permission-checkbox:checked').length;

                    if (total_checkbox == checked_checkbox) {
                        $(this).closest('.permission-card').find('.module-checkbox').prop('checked', true);
                    } else {
                        $(this).closest('.permission-card').find('.module-checkbox').prop('checked', false);
                    }
                });

                $('#roleForm').validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 2,
                            maxlength: 255,
                            remote: {
                                url: "{{ route('admin.validate.role') }}",
                                type: "post",
                                data: {
                                    id: function() {
                                        return $("#id").val();
                                    },
                                    name: function() {
                                        return $("#name").val();
                                    }
                                }
                            }
                        },
                        status: {
                            required: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Please enter a role name.",
                            minlength: "Role name must consist of at least 2 characters.",
                            maxlength: "Role name cannot exceed 255 characters.",
                            remote: "Role name already exists."
                        },
                        status: {
                            required: "Please select a status."
                        }
                    },
                    errorClass: 'text-danger small mt-1',
                    errorElement: 'span',
                    highlight: function(element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid');
                    },
                    errorPlacement: function(error, element) {
                        if (element.hasClass('select2-hidden-accessible')) {
                            error.insertAfter(element.next('.select2-container'));
                        } else if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else if (element.prop('type') === 'radio') {
                            error.insertAfter(element.closest('.d-flex'));
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });
            });
        </script>
    @endpush

</x-master-layout>
