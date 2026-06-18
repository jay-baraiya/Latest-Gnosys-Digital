<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <input type="text" class="form-control" name="name" id="name" placeholder="Name"
                                value="{{ old('name', $category->name ?? '') }}" disabled>
                        </div>
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="type">Type <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="type" id="type" disabled>
                            <option value="">Select Type</option>
                            <option value="product" {{ $category->type == 'product' ? 'selected' : '' }} >Digital Product</option>
                            <option value="service" {{ $category->type == 'service' ? 'selected' : '' }} >Digital Service</option>
                        </select>
                        @error('type')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex gap-3 mb-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status-active"
                            value="1" @if (old('status', isset($category) ? $category->status : 1) == 1) checked @endif disabled>
                        <label class="form-check-label" for="status-active">Active</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status-inactive"
                            value="0" @if (old('status', isset($category) ? $category->status : 1) == 0) checked @endif disabled>
                        <label class="form-check-label" for="status-inactive">Inactive</label>
                    </div>
                </div>
                @error('status')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
            </div>


    </x-form-wrapper>

</x-master-layout>
