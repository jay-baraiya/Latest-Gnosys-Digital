<x-app-layout>
    <div class="pt-24 sm:pt-32 pb-12 sm:pb-16 bg-slate-50 min-h-screen">
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <nav aria-label="Breadcrumb" class="mb-4 sm:mb-6">
                <ol class="flex items-center space-x-1 sm:space-x-2 text-xs sm:text-sm text-slate-500 flex-wrap sm:flex-nowrap">
                    <li>
                        <a href="{{ route('home') }}" class="flex items-center hover:text-blue-600 transition-colors">
                            <i class="ti ti-home mr-1 sm:mr-1.5 text-base sm:text-lg"></i> Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="ti ti-chevron-right text-slate-400 mx-0.5 sm:mx-1 text-sm sm:text-base"></i>
                            <a href="{{ route('services.listing') }}" class="hover:text-blue-600 transition-colors whitespace-nowrap">
                                Digital Services
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="ti ti-chevron-right text-slate-400 mx-0.5 sm:mx-1 text-sm sm:text-base"></i>
                            <span class="text-slate-900 font-semibold truncate max-w-[120px] sm:max-w-md" aria-current="page">
                                {{ $service->name }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>

            <form action="{{ route('addtocart') }}" method="POST">
                @csrf

                <input type="hidden" name="product_id" value="{{ encrypt($service->id) }}">
                <input type="hidden" name="variant_id" id="hidden-variant-id" value="">
                <input type="hidden" name="variant_name" id="hidden-variant-name" value="">
                <input type="hidden" name="product_price" id="hidden-price" value="">
                <input type="hidden" name="product_type" id="hidden-type" value="service">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

                    <div class="lg:col-span-7 relative lg:sticky lg:top-32">
                        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 p-4 sm:p-8 lg:p-12">
                            <div class="relative w-full aspect-[4/3] bg-slate-100 rounded-xl sm:rounded-2xl overflow-hidden group flex items-center justify-center">
                                <div class="absolute inset-0 flex items-center justify-center opacity-50 pointer-events-none">
                                    <div class="w-40 h-40 sm:w-64 sm:h-64 bg-blue-200 rounded-full blur-3xl"></div>
                                </div>
                                <img alt="{{ $service->name }}"
                                    class="w-full max-w-[200px] sm:max-w-sm lg:max-w-md h-auto object-contain z-10 transition-transform duration-500 group-hover:scale-105 drop-shadow-xl"
                                    src="{{ $service->image }}" />
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-5 flex flex-col">
                        <div class="mb-4 sm:mb-6">
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-[10px] sm:text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10 mb-3 sm:mb-4">
                                {{ $service->category->name ?? 'Digital Service' }}
                            </span>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                                {{ $service->name }}
                            </h1>
                            <div class="mt-3 sm:mt-4 flex items-baseline gap-2">
                                <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight product-price_">{{ $service->price_display }}</p>
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 p-5 sm:p-8 mb-6 sm:mb-8">

                            @if($service->variants->count() > 0)
                                <div id="package-selection-wrapper">
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900 mb-3 uppercase tracking-wider">Select Package</h3>
                                    <div class="flex p-1 space-x-1 bg-slate-100 rounded-xl mb-6">
                                        @foreach($service->variants as $index => $variant)
                                            <button type="button" data-variant-id="{{ $variant->id }}"
                                                    class="variant-btn flex-1 py-2 sm:py-2.5 text-xs sm:text-sm font-semibold rounded-lg transition-all duration-200 {{ $index === 0 ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
                                                {{ $variant->name }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-4 product-price"></p>

                            <ul id="product-features-list" class="space-y-3 mb-6 min-h-[100px]">
                            </ul>

                            <hr class="border-slate-100 mb-6">

                            <h3 class="text-xs sm:text-sm font-bold text-slate-900 mb-3 sm:mb-4 uppercase tracking-wider">Service Options</h3>
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-xs sm:text-sm font-medium text-slate-700 mb-1">Delivery Speed</label>
                                    <div class="relative">
                                        <select name="delivery_speed" class="block w-full appearance-none rounded-xl border-slate-300 py-2.5 pl-3 pr-10 text-xs sm:text-sm text-slate-900 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-slate-50 cursor-pointer">
                                            <option value="Standard">Standard Delivery</option>
                                            <option value="Expedited">Expedited Delivery +$200</option>
                                        </select>
                                        <i class="ti ti-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4">
                                <div class="flex items-center justify-between border border-slate-300 rounded-xl bg-slate-50 h-12 sm:h-14 w-full sm:w-32 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all">
                                    <button type="button" id="qty-minus" class="px-4 h-full text-slate-500 hover:text-blue-600 transition-colors focus:outline-none">
                                        <i class="ti ti-minus"></i>
                                    </button>
                                    <input type="number" name="product_qty" id="qty-input" value="1" min="1" class="w-full text-center font-semibold text-slate-900 border-none focus:ring-0 p-0 bg-transparent text-sm sm:text-base" />
                                    <button type="button" id="qty-plus" class="px-4 h-full text-slate-500 hover:text-blue-600 transition-colors focus:outline-none">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>

                                @if (auth()->check())
                                    <button type="submit" class="flex-1 w-full h-12 sm:h-14 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm sm:text-base rounded-xl transition-all shadow-sm hover:shadow-md active:scale-[0.98] flex items-center justify-center gap-2">
                                        <i class="ti ti-shopping-cart-plus text-lg sm:text-xl"></i>
                                        Add to Cart
                                    </button>
                                @else
                                    <a href="#" id="openLoginBtn" class="flex-1 w-full h-12 sm:h-14 bg-gradient-to-b from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 border border-blue-700 text-white font-semibold text-sm sm:text-base rounded-xl transition-all shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.23)] hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                                        <i class="ti ti-shopping-cart-plus text-lg sm:text-xl"></i>
                                        Add to Cart
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 sm:mt-16">
            <div class="border-b border-slate-200 overflow-x-auto hide-scrollbar">
                <nav class="-mb-px flex space-x-6 sm:space-x-8 min-w-max" aria-label="Tabs">
                    <button data-tab="description" class="tab-btn border-blue-600 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Description
                    </button>
                    <button data-tab="reviews" class="tab-btn border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Reviews (0)
                    </button>
                </nav>
            </div>

            <div class="py-6 sm:py-8">
                <div id="tab-description" class="tab-pane prose prose-sm sm:prose-base prose-slate max-w-4xl text-slate-600">
                    </div>

                <div id="tab-reviews" class="tab-pane" style="display: none;">
                    <p class="text-sm sm:text-base text-slate-500 italic">No reviews yet. Be the first to review this service!</p>
                </div>
            </div>
        </section>
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

@section('script')
    <script>
        $(document).ready(function() {
            // 1. Load Data from Laravel Backend
            const serviceData = @json($service);
            const productHasVariants = serviceData.variants && serviceData.variants.length > 0;

            // 2. Format Data for the UI
            let variantsDict = {};
            let currentVariantId = null;

            if (productHasVariants) {
                currentVariantId = serviceData.variants[0].id;

                serviceData.variants.forEach(function(variant) {
                    let featuresArray = variant.features ? variant.features : [];

                    variantsDict[variant.id] = {
                        name: variant.name, // Added for hidden input
                        raw_price: variant.price, // Added for hidden input
                        price: '$' + parseFloat(variant.price).toFixed(2),
                        features: featuresArray,
                        description: `<strong>${variant.name} Package Overview:</strong><br><br>` +
                                     (variant.description ? variant.description.replace(/\n/g, '<br>') : ''),
                        isSingle: false
                    };
                });
            } else {
                $('#package-selection-wrapper').hide();
            }

            const singleProductData = {
                name: serviceData.name, // Added for hidden input
                raw_price: serviceData.price, // Added for hidden input
                price: '$' + parseFloat(serviceData.price).toFixed(2),
                features: serviceData.service_features ? serviceData.service_features : [],
                description: serviceData.description ? serviceData.description.replace(/\n/g, '<br>') : '',
                isSingle: true
            };

            // 3. Render UI Function
            function renderProductDetails() {
                let dataToRender;

                if (productHasVariants) {
                    dataToRender = variantsDict[currentVariantId];

                    $('.variant-btn').removeClass('bg-white text-blue-600 shadow-sm').addClass('text-slate-500 hover:text-slate-700');
                    $(`.variant-btn[data-variant-id="${currentVariantId}"]`).removeClass('text-slate-500 hover:text-slate-700').addClass('bg-white text-blue-600 shadow-sm');
                } else {
                    dataToRender = singleProductData;
                }

                // --- NEW: Update Hidden Form Inputs ---
                $('#hidden-variant-id').val(productHasVariants ? currentVariantId : '');
                $('#hidden-variant-name').val(dataToRender.name);
                $('#hidden-price').val(dataToRender.raw_price);
                // --------------------------------------

                let rawNumber = parseFloat(String(dataToRender.price).replace(/[^0-9.]/g, ''));
                let finalPrice = rawNumber.toLocaleString('en-US', {
                    style: 'currency',
                    currency: 'USD'
                });

                $('.product-price').text(finalPrice);
                $('#tab-description').html(dataToRender.description);

                const $featuresList = $('#product-features-list');
                $featuresList.empty();

                if (dataToRender.features) {
                    if (dataToRender.isSingle) {
                        $.each(dataToRender.features, function(index, feature) {
                            $featuresList.append(`
                                <li class="flex items-center gap-2 sm:gap-3">
                                    <i class="ti ti-circle-check-filled text-emerald-500 text-base sm:text-lg mt-0.5"></i>
                                    <span class="text-xs sm:text-sm font-medium text-slate-600">${feature.name}</span>
                                </li>
                            `);
                        });
                    } else {
                        if (
                            Array.isArray(dataToRender.features) &&
                            dataToRender.features.length > 0 &&
                            dataToRender.features[0] !== null
                        ) {
                            var featureJsonArray = JSON.parse(dataToRender.features);

                            $.each(featureJsonArray, function(index, feature) {
                                $featuresList.append(`
                                <li class="flex items-center gap-2 sm:gap-3">
                                    <i class="ti ti-circle-check-filled text-emerald-500 text-base sm:text-lg mt-0.5"></i>
                                    <span class="text-xs sm:text-sm font-medium text-slate-600">${feature}</span>
                                    </li>
                                `);
                            });
                        }
                    }
                }
            }

            // Initialize Page
            renderProductDetails();

            // --- Event Listeners ---

            $('.variant-btn').on('click', function() {
                currentVariantId = $(this).data('variant-id');
                renderProductDetails();
            });

            let qty = 1;
            $('#qty-plus').on('click', function() {
                qty++;
                $('#qty-input').val(qty);
            });

            $('#qty-minus').on('click', function() {
                if (qty > 1) {
                    qty--;
                    $('#qty-input').val(qty);
                }
            });

            $('#qty-input').on('change', function() {
                let val = parseInt($(this).val());
                if (isNaN(val) || val < 1) {
                    val = 1;
                }
                qty = val;
                $(this).val(qty);
            });

            $('.tab-btn').on('click', function() {
                const targetTab = $(this).data('tab');

                $('.tab-btn').removeClass('border-blue-600 text-blue-600').addClass('border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700');
                $(this).removeClass('border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700').addClass('border-blue-600 text-blue-600');

                $('.tab-pane').hide();
                $('#tab-' + targetTab).show();
            });
        });
    </script>
@endsection
</x-app-layout>
