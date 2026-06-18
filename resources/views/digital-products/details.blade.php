<x-app-layout>
    <div class="pt-24 sm:pt-32 pb-16 bg-slate-50 min-h-screen">

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Breadcrumbs --}}
            <nav aria-label="Breadcrumb" class="mb-4 sm:mb-6">
                <ol class="flex items-center space-x-1 sm:space-x-2 text-xs sm:text-sm text-slate-500 flex-wrap sm:flex-nowrap">
                    <li>
                        <a href="{{ url('home') }}" class="flex items-center hover:text-blue-600 transition-colors font-medium">
                            <i class="ti ti-home mr-1 sm:mr-1.5 text-base sm:text-lg"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="ti ti-chevron-right text-slate-400 mx-0.5 sm:mx-1 text-sm sm:text-base"></i>
                            <a href="{{ route('products.listing') }}" class="hover:text-blue-600 transition-colors whitespace-nowrap font-medium">
                                Digital Products
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="ti ti-chevron-right text-slate-400 mx-0.5 sm:mx-1 text-sm sm:text-base"></i>
                            <span class="text-slate-900 font-semibold truncate max-w-[120px] sm:max-w-md" aria-current="page">
                                {{ $product->name }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

                {{-- Left Side: Image --}}
                <div class="lg:col-span-7 relative lg:sticky lg:top-32">
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8 lg:p-12">
                        <div class="relative w-full aspect-[4/3] bg-slate-50 rounded-xl sm:rounded-2xl overflow-hidden group flex items-center justify-center">
                            <div class="absolute inset-0 flex items-center justify-center opacity-50 pointer-events-none">
                                <div class="w-40 h-40 sm:w-64 sm:h-64 bg-blue-200 rounded-full blur-3xl"></div>
                            </div>
                            <img alt="{{ $product->name }} {{ $product->image }}"
                                class="w-full max-w-[200px] sm:max-w-sm lg:max-w-md h-auto object-contain z-10 transition-transform duration-500 group-hover:scale-105 drop-shadow-xl"
                                src="{{ $product->image ? asset($product->image) : asset('assets/img/default-product.jpg') }}" />
                        </div>
                    </div>
                </div>

                {{-- Right Side: Details --}}
                <div class="lg:col-span-5 flex flex-col">

                    <div class="mb-4 sm:mb-6">
                        <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-[10px] sm:text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10 mb-3 sm:mb-4">
                            {{ $product->category->name ?? 'Digital Product' }}
                        </span>

                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            {{ $product->name }}
                        </h1>

                        {{-- Pricing Logic --}}
                        <div class="mt-3 sm:mt-4 flex items-baseline gap-2">
                            @if($product->on_sale && $product->price_for_sale)
                                <p class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">${{ number_format($product->price_for_sale, 2) }}</p>
                                <p class="text-lg sm:text-xl font-medium text-slate-400 line-through">${{ number_format($product->price, 2) }}</p>
                            @else
                                <p class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">${{ number_format($product->price, 2) }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 p-5 sm:p-8 mb-6 sm:mb-8">

                        <p class="text-sm sm:text-base text-slate-600 mb-6 sm:mb-8 leading-relaxed">
                            {{ $product->short_description ?? Str::limit(strip_tags($product->description), 200) }}
                        </p>

                        <hr class="border-slate-100 mb-6">

                        {{-- Add to Cart Form --}}
                        <form action="{{ route('addtocart') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3 sm:gap-4 mb-6 sm:mb-8" x-data="{ qty: 1 }">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ encrypt($product->id) }}">
                            <input type="hidden" name="product_price" id="hidden-price" value="{{ $product->price }}">
                            <input type="hidden" name="product_type" id="hidden-type" value="product">

                            <div class="flex items-center justify-between border border-slate-300 rounded-xl bg-slate-50 h-12 sm:h-14 w-full sm:w-36 focus-within:border-blue-500 focus-within:ring-1 focus-within:ring-blue-500 transition-all shadow-sm">
                                <button type="button" @click="qty > 1 ? qty-- : null" aria-label="Decrease quantity" class="px-4 h-full text-slate-500 hover:text-blue-600 transition-colors focus:outline-none">
                                    <i class="ti ti-minus"></i>
                                </button>
                                <input name="product_qty" aria-label="Quantity" x-model="qty" class="w-full text-center font-bold text-slate-900 border-none focus:ring-0 p-0 bg-transparent text-sm sm:text-base" min="1" type="number" />
                                <button type="button" @click="qty++" aria-label="Increase quantity" class="px-4 h-full text-slate-500 hover:text-blue-600 transition-colors focus:outline-none">
                                    <i class="ti ti-plus"></i>
                                </button>
                            </div>

                            @if (auth()->check())
                                <button type="submit" class="flex-1 w-full h-12 sm:h-14 bg-gradient-to-b from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 border border-blue-700 text-white font-semibold text-sm sm:text-base rounded-xl transition-all shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.23)] hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                                    <i class="ti ti-shopping-cart-plus text-lg sm:text-xl"></i>
                                    Add to Cart
                                </button>
                            @else
                                <a href="#" id="openLoginBtn" class="flex-1 w-full h-12 sm:h-14 bg-gradient-to-b from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 border border-blue-700 text-white font-semibold text-sm sm:text-base rounded-xl transition-all shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.23)] hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                                    <i class="ti ti-shopping-cart-plus text-lg sm:text-xl"></i>
                                    Add to Cart
                                </a>
                            @endif
                        </form>

                    </div>

                    {{-- Meta Info --}}
                    <div class="space-y-4 sm:space-y-5">
                        <div class="flex items-center gap-2 text-xs sm:text-sm">
                            <span class="font-bold text-slate-900 uppercase tracking-wider text-[10px] sm:text-xs">SKU:</span>
                            <span class="text-slate-600 font-medium">{{ $product->sku ?? 'N/A' }}</span>
                        </div>

                        @if($product->tags)
                        <div class="flex items-start sm:items-center gap-2 sm:gap-3 flex-col sm:flex-row">
                            <span class="font-bold text-slate-900 uppercase tracking-wider text-[10px] sm:text-xs mt-1 sm:mt-0">Tags:</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $product->tags) as $tag)
                                    <a href="#" class="text-[10px] sm:text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 px-3 py-1.5 rounded-full transition-colors border border-slate-200/60">
                                        {{ trim($tag) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
                            <span class="font-bold text-slate-900 uppercase tracking-wider text-[10px] sm:text-xs">Share:</span>
                            <div class="flex gap-2">
                                <a href="#" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-500 hover:bg-[#0A66C2] hover:text-white hover:border-[#0A66C2] transition-all">
                                    <i class="ti ti-brand-linkedin text-base sm:text-lg"></i>
                                </a>
                                <a href="#" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-500 hover:bg-black hover:text-white hover:border-black transition-all">
                                    <i class="ti ti-brand-x text-base sm:text-lg"></i>
                                </a>
                                <a href="#" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-500 hover:bg-[#1877F2] hover:text-white hover:border-[#1877F2] transition-all">
                                    <i class="ti ti-brand-facebook text-base sm:text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        {{-- Description & Reviews Tabs --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-16" x-data="{ tab: 'description' }">
            <div class="border-b border-slate-200 overflow-x-auto hide-scrollbar">
                <nav class="-mb-px flex space-x-6 sm:space-x-8 min-w-max" aria-label="Tabs">
                    <button @click="tab = 'description'" :class="{'border-blue-600 text-blue-600': tab === 'description', 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700': tab !== 'description'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Description
                    </button>
                    <button @click="tab = 'reviews'" :class="{'border-blue-600 text-blue-600': tab === 'reviews', 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700': tab !== 'reviews'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                        Reviews (0)
                    </button>
                </nav>
            </div>

            <div class="py-6 sm:py-8">
                <div x-show="tab === 'description'" class="prose prose-sm sm:prose-base prose-slate max-w-4xl text-slate-600">
                    {{-- Outputs the rich text description from your database --}}
                    {!! $product->description !!}
                </div>

                <div x-show="tab === 'reviews'" style="display: none;">
                    <p class="text-sm sm:text-base text-slate-500 italic">No reviews yet. Be the first to review this product!</p>
                </div>
            </div>
        </section>

        @if($relatedProducts->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 sm:mt-16 pt-10 sm:pt-16 border-t border-slate-200">
            <div class="flex items-center justify-between mb-6 sm:mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Related Products</h2>
                <a href="{{ route('products.listing', ['category' => $product->category_id]) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    View All →
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedProducts as $related)
                    <div class="group flex flex-col rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5 overflow-hidden hover:shadow-md transition-all h-full">

                        {{-- Image Container --}}
                        <div class="aspect-4/3 w-full overflow-hidden bg-slate-100 relative">
                            <a href="{{ route('products.show', $related->slug) }}">
                                <img src="{{ $related->image ? asset($related->image) : asset('assets/img/default-product.jpg') }}" class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105" alt="{{ $related->name }}" />
                            </a>

                            {{-- Optional Badge support integrated into your new design --}}
                            @if($related->badge)
                                <div class="absolute top-3 left-3 bg-slate-900/90 backdrop-blur-sm px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider font-bold text-white shadow-sm pointer-events-none">
                                    {{ $related->badge }}
                                </div>
                            @endif
                        </div>

                        {{-- Product Details --}}
                        <div class="p-6 flex flex-col flex-1">
                            <h3 class="text-base font-semibold text-slate-900 hover:text-blue-600 transition-colors">
                                <a href="{{ route('products.show', $related->slug) }}">
                                    {{ $related->name }}
                                </a>
                            </h3>

                            <p class="mt-2 text-sm text-slate-500 line-clamp-2">
                                {{ $related->short_description ?? Str::limit(strip_tags($related->description), 80) }}
                            </p>

                            <div class="mt-auto pt-6 flex items-center justify-between">

                                {{-- Dynamic Pricing --}}
                                <div class="flex flex-col">
                                    @if($related->on_sale && $related->price_for_sale)
                                        <div class="flex items-center gap-2">
                                            <p class="text-lg font-bold text-slate-900">${{ number_format($related->price_for_sale, 2) }}</p>
                                            <p class="text-sm font-medium text-slate-400 line-through">${{ number_format($related->price, 2) }}</p>
                                        </div>
                                    @elseif($related->price > 0)
                                        <p class="text-lg font-bold text-slate-900">${{ number_format($related->price, 2) }}</p>
                                    @else
                                        <p class="text-lg font-bold text-green-600">Free</p>
                                    @endif
                                </div>

                                {{-- Details --}}
                                <a href="{{ route('products.show', $product->slug) }}" class="group inline-flex items-center justify-center rounded-full py-2 px-4 text-sm font-semibold focus-visible:outline-2 focus-visible:outline-offset-2 bg-slate-900 text-white hover:bg-slate-800 focus-visible:outline-slate-900 transition-colors">
                                    Details
                                </a>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif

    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>
