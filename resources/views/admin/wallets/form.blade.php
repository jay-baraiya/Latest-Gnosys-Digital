<x-master-layout>
    <x-form-wrapper action="{{ isset($action) ? $action : 'Create' }}">
        <form id="walletForm" action="{{ route('admin.wallets.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="buyer_id">Buyer <span class="text-danger">*</span></label>
                        <select class="form-select select2" name="buyer_id" id="buyer_id">
                            <option value="">Select Buyer</option>
                            @if (isset($buyers) && count($buyers) > 0)
                                @foreach ($buyers as $buyer)
                                    <option value="{{ $buyer->id }}"
                                        {{ old('buyer_id') == $buyer->id ? 'selected' : '' }}>
                                        {{ $buyer->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('buyer_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="amount">Amount <span class="text-danger">*</span></label>
                        <div class="input-group mb-1">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control @error('amount') is-invalid @enderror" name="amount" id="amount" placeholder="0.00"
                                value="{{ old('amount') }}">
                        </div>
                        @error('amount')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="date">Date <span class="text-danger">*</span></label>
                        <input type="datetime-local" class="form-control @error('date') is-invalid @enderror" name="date" id="date"
                            value="{{ old('date', date('Y-m-d\TH:i')) }}">
                        @error('date')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="status">Status <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" id="status">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="transaction_id">Transaction ID</label>
                        <input type="text" class="form-control @error('transaction_id') is-invalid @enderror" name="transaction_id" id="transaction_id"
                            value="{{ old('transaction_id') }}" placeholder="Enter Transaction ID">
                        @error('transaction_id')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="proof">Proof Document</label>
                        <input type="file" class="form-control @error('proof') is-invalid @enderror" name="proof" id="proof" accept="image/jpeg,image/png,image/jpg,application/pdf">
                        @error('proof')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label" for="note">Note</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" name="note" id="note" rows="3" placeholder="Enter Note">{{ old('note', $wallet->note ?? '') }}</textarea>
                        @error('note')
                        <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                @if(isset($wallet))
                    @if(!empty($wallet->reject_reason))
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="reject_reason">Reject Reason</label>
                            <textarea class="form-control" name="reject_reason" id="reject_reason" rows="3" readonly>{{ $wallet->reject_reason }}</textarea>
                        </div>
                    </div>
                    @endif

                    @if(!empty($wallet->reapprove_reason))
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" for="reapprove_reason">Reapprove Reason</label>
                            <textarea class="form-control" name="reapprove_reason" id="reapprove_reason" rows="3" readonly>{{ $wallet->reapprove_reason }}</textarea>
                        </div>
                    </div>
                    @endif
                @endif
            </div>

            <div class="text-end mt-3">
                <a href="{{ route($moduleUrl) }}" class="btn btn-soft-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </x-form-wrapper>

    @push('scripts')
        <script>
            $(document).ready(function() {
                
                $('#buyer_id').select2({
                    placeholder: 'Select Buyer',
                    allowClear: true,
                });

                $('#walletForm').validate({
                    rules: {
                        buyer_id: {
                            required: true
                        },
                        amount: {
                            required: true,
                            number: true,
                            min: 1,
                            max: 50000
                        },
                        date: {
                            required: true
                        },
                        status: {
                            required: true
                        }
                    },
                    messages: {
                        buyer_id: {
                            required: "Please select a buyer."
                        },
                        amount: {
                            required: "Please enter wallet amount.",
                            number: "Amount must be a valid number.",
                            min: "Minimum amount is $1.",
                            max: "Maximum amount is $50,000."
                        },
                        date: {
                            required: "Please enter date."
                        },
                        status: {
                            required: "Please select status."
                        }
                    },
                    errorClass: 'text-danger small mt-1',
                    errorElement: 'span',
                    ignore: ":hidden:not(.select2-hidden-accessible)",
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
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

            });
        </script>
    @endpush

</x-master-layout>
