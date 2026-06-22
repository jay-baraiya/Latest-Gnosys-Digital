<x-master-layout>
    <x-form-wrapper>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="name">Name</label>
                    <div class="input-group mb-1">
                        <input type="text" class="form-control" name="name" id="name" placeholder="Name" value="{{ $user->name }}" disabled>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-group mb-1">
                        <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{{ $user->email }}" disabled>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="phone">Phone</label>
                    <div class="input-group mb-1">
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone" value="{{ $user->phone ?? 'N/A' }}" disabled>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label" for="zip">Zip</label>
                    <div class="input-group mb-1">
                        <input type="text" class="form-control" name="zip" id="zip" placeholder="Zip" value="{{ $user->zip ?? 'N/A' }}" disabled>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label" for="address">Address</label>
                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Address" disabled>{{ $user->address ?? 'N/A' }}</textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 d-flex gap-3 mb-1">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="status-active" value="1" 
                        @if($user->status == 1) checked @endif disabled>
                    <label class="form-check-label" for="status-active">Active</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="status" id="status-inactive" value="0" 
                        @if($user->status == 0) checked @endif disabled>
                    <label class="form-check-label" for="status-inactive">Inactive</label>
                </div>
            </div>
        </div>

        <div class="text-end mt-3">
            <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
        </div>
    </x-form-wrapper>
</x-master-layout>
