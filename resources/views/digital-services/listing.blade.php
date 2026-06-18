<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-16">

        {{-- Breadcrumbs --}}
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center space-x-2 text-sm text-slate-500 flex-wrap sm:flex-nowrap">
                <li>
                    <a href="{{ route('home') }}" class="flex items-center hover:text-blue-600 transition-colors">
                        <i class="ti ti-home mr-1.5 text-lg"></i>
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="ti ti-chevron-right text-slate-400 mx-1 text-base"></i>
                        <span class="text-slate-900 font-semibold truncate max-w-[150px] sm:max-w-md" aria-current="page">
                            Digital Services
                        </span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-2">Digital Marketplace</h1>
            <p class="text-lg text-slate-500">Premium technical assets and frameworks for digital pioneers.</p>
        </div>

        {{-- Search & Filters Form --}}
        <form action="{{ route('services.listing') }}" method="GET" class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">

            {{-- Search Input --}}
            <div class="relative w-full md:w-96">
                <i class="ti ti-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                <input
                    name="search"
                    value="{{ request('search') }}"
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors"
                    placeholder="Search products, templates..." type="text" />
            </div>

            {{-- Dropdowns & Submit Button --}}
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">

                {{-- Category Select --}}
                <div class="relative w-full sm:w-44">
                    <select name="category" class="w-full appearance-none pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors cursor-pointer">
                        <option value="">All Categories</option>
                        @if (isset($categorys))
                            @foreach ($categorys as $key => $category)
                                <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        @endif
                    </select>
                    {{-- <i class="ti ti-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i> --}}
                </div>

                {{-- Sort Select --}}
                <div class="relative w-full sm:w-48">
                    <select name="sort" class="w-full appearance-none pl-4 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors cursor-pointer">
                        <option value="">Sort by: Featured</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                    </select>
                    {{-- <i class="ti ti-arrows-sort absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i> --}}
                </div>

                {{-- Apply Button --}}
                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 flex items-center justify-center gap-2">
                    <i class="ti ti-filter hidden sm:inline-block"></i> Apply
                </button>

            </div>
        </form>

        {{-- Dynamic Product Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">

            @forelse ($digitalServices as $service)
                <div class="group flex flex-col rounded-2xl bg-white shadow-sm ring-1 ring-slate-900/5 overflow-hidden hover:shadow-md transition-all h-full">

                    {{-- Image Container --}}
                    <div class="aspect-[4/3] w-full overflow-hidden bg-slate-100 relative">
                        <a href="{{ route('products.show', $service->slug) }}">
                            <img src="{{ $service->image ? asset($service->image) : asset('assets/img/default-product.jpg') }}"
                                class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105"
                                alt="{{ $service->name }}" />
                        </a>

                        {{-- Top Left Badges --}}
                        <div class="absolute top-3 left-3 flex flex-col gap-2 pointer-events-none">
                            {{-- Category Badge --}}
                            <div class="bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider font-bold text-slate-700 shadow-sm border border-slate-100 w-fit">
                                {{ $service->category->name ?? 'Digital' }}
                            </div>

                            {{-- Custom Badge (If exists) --}}
                            @if($service->badge)
                                <div class="bg-slate-900/90 backdrop-blur-sm px-2.5 py-1 rounded-lg text-[10px] uppercase tracking-wider font-bold text-white shadow-sm w-fit">
                                    {{ $service->badge }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Product Details --}}
                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="text-base font-semibold text-slate-900 hover:text-blue-600 transition-colors">
                            <a href="{{ route('services.show', $service->slug) }}">
                                {{ $service->name }}
                            </a>
                        </h3>

                        <p class="mt-2 text-sm text-slate-500 line-clamp-2">
                            {{ $service->short_description ?? Str::limit(strip_tags($service->description), 80) }}
                        </p>

                        <div class="mt-auto pt-6 flex items-center justify-between">

                            {{-- Dynamic Pricing --}}
                            <div class="flex flex-col">
                                @if($service->on_sale && $service->price_for_sale)
                                    <div class="flex items-center gap-2">
                                        <p class="text-lg font-bold text-slate-900">${{ number_format($service->price_for_sale, 2) }}</p>
                                        <p class="text-sm font-medium text-slate-400 line-through">${{ number_format($service->price, 2) }}</p>
                                    </div>
                                @elseif($service->price > 0)
                                    <p class="text-lg font-bold text-slate-900">{{ $service->price_display }}</p>
                                @else
                                    <p class="text-lg font-bold text-green-600">Free</p>
                                @endif
                            </div>

                            {{-- Details Button --}}
                            <a href="{{ route('services.show', $service->slug) }}" class="group inline-flex items-center justify-center rounded-full py-2 px-4 text-sm font-semibold focus-visible:outline-2 focus-visible:outline-offset-2 bg-slate-900 text-white hover:bg-slate-800 focus-visible:outline-slate-900 transition-colors">
                                Details
                            </a>
                        </div>

                    </div>
                </div>

            @empty
                {{-- Empty State --}}
                <div class="col-span-full flex flex-col items-center justify-center py-16 px-4 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4">
                        <i class="ti ti-package-off text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-1">No products found</h3>
                    <p class="text-slate-500 text-center">We couldn't find any products matching your current filters.</p>
                </div>
            @endforelse

        </div>

        {{-- Pagination --}}
        {{ $digitalServices->links('vendor.pagination.custom') }}

    </div>
</x-app-layout>
