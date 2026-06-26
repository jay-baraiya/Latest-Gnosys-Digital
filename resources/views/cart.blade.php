<x-app-layout>
    @if ($carts->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-16">
            <h1 class="font-headline-lg text-headline-lg text-text-primary mb-8 lg:mb-12">
                Your Cart ({{ auth()->check() ? auth()->user()?->getCartItems?->count() : count(session('cart', [])) }} items)
            </h1>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
                <div class="lg:col-span-8">
                    <div class="space-y-4 sm:space-y-6">

                        @foreach ($carts as $cart)
                            <div class="bg-surface-container-lowest border border-border-subtle rounded-lg p-4 sm:p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-8 group cart-item-card"
                            data-id="{{ encrypt($cart->product_id) }}" data-variant-id="{{ encrypt($cart->variant_id) }}" data-product-type="{{ $cart->product_type }}" data-price="{{ $cart->product_price }}">
                                <div class="flex items-start gap-4 sm:gap-6 flex-grow w-full sm:w-auto">
                                    <div class="w-24 h-24 sm:w-32 sm:h-32 flex-shrink-0 bg-surface-container rounded-lg overflow-hidden border border-border-subtle relative">
                                        <img alt="{{ $cart->product_title }}" class="w-full h-full object-cover" data-alt="{{ $cart->product_title }}" src="{{ $cart->product_img }}" />
                                    </div>
                                    <div class="flex-grow space-y-1 sm:space-y-2">
                                        <span class="text-label-sm font-label-sm text-primary uppercase tracking-wider font-semibold">
                                            {{ ucfirst($cart->product_type) }}
                                        </span>
                                        <h3 class="font-headline-md text-lg sm:text-[20px] font-bold text-text-primary leading-tight">
                                            {{ $cart->product_title }}
                                        </h3>
                                        <p class="text-secondary font-body-md text-sm sm:text-base">
                                            @if (!empty($cart->variant_name))
                                            <span>Variant:</span> {{ ucfirst($cart->variant_name) }}
                                            @endif
                                        </p>
                                        <div class="mt-2 flex items-center gap-4 sm:hidden">
                                            <span class="font-bold text-lg text-primary">
                                                @php
                                                    $p_price = $cart->product_price;
                                                    $p_qty = $cart->product_qty ?? 0;

                                                    $total_price = $p_price * $p_qty;
                                                @endphp
                                                ${{ number_format($total_price, 2)  }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4 sm:gap-6 pt-4 sm:pt-0 border-t border-border-subtle sm:border-t-0">
                                    <div class="inline-flex items-center border border-border-subtle rounded-lg p-1 bg-surface-container-lowest shadow-sm">

                                        <button type="button" aria-label="Decrease quantity" class="w-8 h-8 flex items-center justify-center text-secondary hover:text-primary hover:bg-surface-container-low rounded-md transition-colors qty-btn cart-item-qty-minus-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /></svg>
                                        </button>

                                        <div class="w-10 text-center font-body-md-medium">
                                            <input
                                                type="number"
                                                name="product_qty"
                                                id="qty-input"
                                                value="{{ $cart->product_qty ?? 0 }}"
                                                min="1"
                                                class="w-full text-center font-semibold text-slate-900 border-none focus:ring-0 p-0 bg-transparent text-sm sm:text-base [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            />
                                        </div>

                                        <button type="button" aria-label="Increase quantity" class="w-8 h-8 flex items-center justify-center text-secondary hover:text-primary hover:bg-surface-container-low rounded-md transition-colors qty-btn cart-item-qty-plus-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                        </button>

                                    </div>

                                    @php
                                        $p_price = $cart->product_price;
                                        $p_qty = $cart->product_qty ?? 0;

                                        $total_price = $p_price * $p_qty;
                                    @endphp
                                    <div class="hidden sm:block text-right min-w-[100px]">
                                        <span class="font-bold text-lg text-primary block row-total" data-raw="{{ $total_price }}">
                                            ${{ number_format($total_price, 2)  }}
                                        </span>
                                    </div>

                                    <button class="text-secondary hover:text-error transition-colors p-2 rounded-lg hover:bg-error-container/50 ml-auto sm:ml-0 flex items-center justify-center cart-item-remove-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach

                    </div>

                    <div class="mt-6 sm:mt-8 flex justify-start">
                        <a class="flex items-center text-primary font-body-md-medium hover:underline gap-2" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                            Continue Shopping
                        </a>
                    </div>
                </div>

                <aside class="lg:col-span-4 lg:sticky lg:top-28">
                    <div class="bg-surface-container-low border border-border-subtle rounded-lg p-6 sm:p-8 shadow-md">
                        <h2 class="font-headline-md text-headline-md font-bold text-text-primary mb-6 border-b border-border-subtle pb-4">Order Summary</h2>

                        <div class="space-y-4 mb-8">
                            <div class="flex justify-between text-body-md text-secondary">
                                <span>Subtotal</span>
                                <span class="text-on-background font-medium" id="subtotal">
                                    ${{ number_format($grandTotal,2) }}
                                </span>
                            </div>
                            {{-- <div class="flex justify-between text-body-md text-secondary">
                                <span>Estimated Tax (8%)</span>
                                <span class="text-on-background font-medium">$203.92</span>
                            </div>
                            <div class="flex justify-between text-body-md text-secondary">
                                <span>Digital Delivery</span>
                                <span class="text-tertiary-container font-medium">Free</span>
                            </div> --}}
                        </div>

                        <div class="pt-6 border-t border-border-subtle mb-8">
                            <div class="flex justify-between items-center">
                                <span class="font-headline-md text-headline-md font-bold text-text-primary">Total</span>
                                <span class="text-3xl sm:text-4xl font-extrabold text-primary" id="grandTotal">
                                    ${{ number_format($grandTotal,2) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-label-sm font-label-sm text-secondary mb-2" for="coupon">Discount Code</label>
                            <div class="flex gap-2 w-full">
                                <input class="flex-grow w-full rounded-lg border-border-subtle focus:ring-2 focus:ring-primary focus:border-primary px-4 py-2 text-body-md outline-none bg-surface-container-lowest" id="coupon" placeholder="GNOSYS20" type="text" />
                                <button class="flex-shrink-0 pill-button px-4 sm:px-6 py-2 border border-primary text-primary font-body-md-medium hover:bg-primary hover:text-white transition-colors">Apply</button>
                            </div>
                        </div>

                        <a href="{{ route('checkout') }}" class="w-full pill-button bg-primary text-on-primary py-3 sm:py-4 px-6 sm:px-8 font-headline-md text-[18px] sm:text-[20px] font-bold flex items-center justify-center gap-3 shadow-lg hover:shadow-xl hover:bg-primary/90 transition-all">
                            Proceed to Checkout
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M13 18l6 -6" /><path d="M13 6l6 6" /></svg>
                        </a>

                        <div class="mt-6 sm:mt-8 space-y-4">
                            <div class="flex items-center gap-3 text-secondary text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M11.46 20.846a12 12 0 0 1 -7.96 -14.846a12 12 0 0 0 8.5 -3a12 12 0 0 0 8.5 3a12 12 0 0 1 -1.116 9.376" /><path d="M15 19l2 2l4 -4" /></svg>
                                <span>Secure SSL Encrypted Checkout</span>
                            </div>
                            <div class="flex items-center gap-3 text-secondary text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                <span>Instant access to digital assets</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        @section('script')
            <script>
                $(document).ready(function () {
                    // Coupon accordion
                    $('#couponToggle').on('click', function () {
                        $(this).toggleClass('open');
                        $('#couponDrawer').slideToggle(280);
                    });
                });

                $(document).ready(function() {
                    let typingTimer;
                    const doneTypingInterval = 500;

                    $('.cart-item-qty-plus-btn, .cart-item-qty-minus-btn').on('click', function(e) {
                        e.preventDefault();

                        let $btn = $(this);

                        let $card = $btn.closest('.cart-item-card');

                        let productId = $card.data('id');
                        let variantId = $card.data('variant-id');
                        let productType = $card.data('product-type');

                        let $input = $card.find('#qty-input');
                        let currentQty = parseInt($input.val()) || 1;

                        if ($btn.hasClass('cart-item-qty-plus-btn')) {
                            currentQty += 1;
                        } else if ($btn.hasClass('cart-item-qty-minus-btn')) {
                            if (currentQty > 1) {
                                currentQty -= 1;
                            } else {
                                return;
                            }
                        }

                        $input.val(currentQty);

                        clearTimeout(typingTimer);

                        $card.find('.qty-btn').prop('disabled', true);

                        typingTimer = setTimeout(function() {
                            sendCartAjax(productId, variantId, productType, currentQty, $card);
                        }, doneTypingInterval);
                    });

                    $(document).on('input', '#qty-input', function(e) {
                        e.preventDefault();

                        let $btn = $(this);

                        let $card = $btn.closest('.cart-item-card');

                        let productId = $card.data('id');
                        let variantId = $card.data('variant-id');
                        let productType = $card.data('product-type');

                        // let $input = $card.find('#qty-input');
                        let currentQty = parseInt($(this).val()) || 1;

                        clearTimeout(typingTimer);

                        $card.find('.qty-btn').prop('disabled', true);

                        typingTimer = setTimeout(function() {
                            sendCartAjax(productId, variantId, productType, currentQty, $card);
                        }, doneTypingInterval);
                    });

                    function sendCartAjax(productId, variantId, productType, qty, $card) {

                        let unitPrice = parseFloat($card.data('price')) || 0;

                        $.ajax({
                            url: '{{ route('cart.update') }}',
                            type: 'POST',
                            data: {
                                product_id: productId,
                                variant_id: variantId,
                                product_type : productType,
                                quantity: qty,
                            },
                            success: function(response) {

                                let newRowTotal = unitPrice * qty;

                                $card.find('.row-total').attr('data-raw', newRowTotal);

                                $card.find('.row-total').text('$' + newRowTotal.toFixed(2));

                                updateGrandTotal();
                            },
                            error: function(xhr) {
                                alert('Something went wrong updating the quantity.');
                            },
                            complete: function() {
                                $card.find('.qty-btn').prop('disabled', false);
                            }
                        });
                    }

                    function updateGrandTotal() {
                        let grandTotal = 0;

                        $('.row-total').each(function() {
                            let rowPrice = parseFloat($(this).attr('data-raw')) || 0;
                            grandTotal += rowPrice;
                        });

                        if (grandTotal == '0' || grandTotal == '0.00') {
                            $('#cartItemsList').html('<div class="container pt-5 d-flex justify-content-center align-items-center"><div class="col-12 col-md-8 col-lg-6 text-center p-5 bg-white rounded shadow-sm border"><div class="mb-4 text-secondary"><svg xmlns="http://www.w3.org/2000/svg" width="90" height="90" fill="currentColor" class="bi bi-cart-x opacity-75" viewBox="0 0 16 16"><path d="M7.354 5.646a.5.5 0 1 0-.708.708L7.793 7.5 6.646 8.646a.5.5 0 1 0 .708.708L8.5 8.207l1.146 1.147a.5.5 0 0 0 .708-.708L9.207 7.5l1.147-1.146a.5.5 0 0 0-.708-.708L8.5 6.793 7.354 5.646z"/><path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zm3.915 10L3.102 4h10.796l-1.313 7h-8.17zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg></div><h2 class="fw-bold text-dark mb-3">Your Cart is Empty!</h2><p class="text-muted mb-5 px-3">Looks like you haven\'t added anything to your cart yet. Discover some amazing products and start shopping.</p><a href="{{ url('/') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm fw-semibold">&larr; Back to Home</a></div></div>');

                            $('.checkoutSection').remove();
                            $('.cartItemSection').addClass('col-lg-8');
                            $('.cartItemSection').removeClass('col-lg-8');
                        }

                        $('#subtotal').text('$' + grandTotal.toFixed(2));
                        $('#grandTotal').text('$' + grandTotal.toFixed(2));
                    }

                    $('.cart-item-remove-btn').on('click', function(e) {
                        e.preventDefault();

                        let $btn = $(this);
                        let $card = $btn.closest('.cart-item-card');
                        let productId = $card.data('id');
                        let variantId = $card.data('variant-id');
                        let productType = $card.data('product-type');

                        if (!confirm('Are you sure you want to remove this item from your cart?')) {
                            return;
                        }

                        $btn.prop('disabled', true);

                        $.ajax({
                            url: '{{ route('cart.remove') }}',
                            type: 'POST',
                            data: {
                                product_id: productId,
                                variant_id: variantId,
                                product_type : productType
                            },
                            success: function(response) {
                                $card.fadeOut(300, function() {
                                    $(this).remove();

                                    $('.cart-count-badge').html(response.cart);

                                    updateGrandTotal();
                                });
                            },
                            error: function(xhr) {
                                alert('Something went wrong trying to remove the item.');
                                $btn.prop('disabled', false);
                            }
                        });
                    });
                });
            </script>
        @endsection
    @else
        <div class="max-w-7xl mx-auto px-4 pt-32 pb-16 sm:px-6 lg:px-8 py-16 sm:py-24 flex items-center justify-center min-h-[70vh]">

            <div class="max-w-lg w-full bg-white border border-gray-200 rounded-[2rem] shadow-sm p-8 sm:p-12 text-center">

                <div class="inline-flex items-center justify-center w-32 h-32 rounded-full bg-blue-50 mb-8 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-blue-500" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                        <path d="M17 17h-11v-14h-2"></path>
                        <path d="M6 5l14 1l-1 7h-13"></path>
                    </svg>

                    <div class="absolute top-4 right-4 w-5 h-5 bg-white rounded-full flex items-center justify-center shadow-sm">
                        <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                    </div>
                </div>

                <h2 class="text-3xl font-extrabold text-gray-900 mb-4">Your cart is empty</h2>
                <p class="text-base text-gray-500 mb-10 leading-relaxed">
                    Looks like you haven't added any digital assets or services to your cart yet. Discover our latest products and start building!
                </p>

                <div class="flex flex-col gap-4">
                    <a href="{{ route('products.listing') }}" class="w-full inline-flex justify-center items-center gap-2 px-8 py-4 bg-blue-600 text-white rounded-full font-semibold text-lg hover:bg-blue-700 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 shadow-lg shadow-blue-600/30">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                            <path d="M21 21l-6 -6"></path>
                        </svg>
                        Browse Products
                    </a>

                    <a href="{{ route('profile.edit') }}#orders" class="w-full inline-flex justify-center items-center gap-2 px-8 py-4 bg-white border border-gray-200 text-gray-700 rounded-full font-semibold text-base hover:bg-gray-50 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M12 8l0 4l2 2"></path>
                            <path d="M3.05 11a9 9 0 1 1 .5 4m-.5 5v-5h5"></path>
                        </svg>
                        View Past Orders
                    </a>
                </div>

            </div>
        </div>
    @endif
</x-app-layout>
