<x-app-layout>
    <div class="bg-slate-50 min-h-screen pb-16">

        <section class="pt-32 pb-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-start justify-center">
                <span class="text-blue-600 font-bold tracking-widest uppercase text-xs mb-4 block">
                    The Journal
                </span>
                <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight max-w-3xl leading-[1.15]">
                    Insights & Innovation
                </h1>
                <p class="text-lg text-slate-600 max-w-2xl leading-relaxed">
                    Exploring the intersection of engineering excellence, digital transformation, and the future of enterprise technology.
                </p>
            </div>
        </section>

        <section class="sticky top-[72px] lg:top-[80px] z-40 bg-white/80 backdrop-blur-md border-y border-slate-200 py-4 mb-10 shadow-sm">

            {{-- Changed the inner div to a form so the filters actually submit --}}
            <form action="#" method="GET" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">

                {{-- Search Input (Design unchanged) --}}
                <div class="relative flex-grow max-w-md w-full">
                    <i class="ti ti-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                    <input
                        name="search"
                        value="{{ request('search') }}"
                        class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 text-sm text-slate-900 transition-all placeholder:text-slate-400"
                        placeholder="Search insights, guides, updates..." type="text" />
                </div>

                {{-- Dropdown & Filter Button --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">

                    {{-- Category Dropdown --}}
                    <div class="relative w-full sm:w-56">
                        <select name="category" class="w-full appearance-none pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-colors cursor-pointer">
                            <option value="">All Categories</option>
                            <option value="engineering" {{ request('category') == 'engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="digital-strategy" {{ request('category') == 'digital-strategy' ? 'selected' : '' }}>Digital Strategy</option>
                            <option value="ai-automation" {{ request('category') == 'ai-automation' ? 'selected' : '' }}>AI & Automation</option>
                            <option value="case-studies" {{ request('category') == 'case-studies' ? 'selected' : '' }}>Case Studies</option>
                        </select>
                        {{-- <i class="ti ti-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i> --}}
                    </div>

                    {{-- Filter Submit Button --}}
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl transition-all shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 flex items-center justify-center gap-2">
                        <i class="ti ti-filter hidden sm:inline-block"></i> Apply
                    </button>

                </div>
            </form>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">

                @forelse ($blogs as $blog)
                    <article class="group flex flex-col bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="aspect-[16/10] relative overflow-hidden bg-slate-100">
                            <img alt="{{ $blog->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                                src="{{ $blog->image }}" />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/20 to-transparent pointer-events-none"></div>
                            <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm text-slate-900 text-[10px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-full shadow-sm">
                                {{ $blog->category->name ?? 'Uncategorized' }}
                            </div>
                        </div>
                        <div class="p-6 sm:p-8 flex flex-col flex-grow">
                            <div class="flex items-center text-xs text-slate-500 mb-3 font-semibold tracking-wide">
                                <span>{{ \Carbon\Carbon::parse($blog->datetime)->format('M d, Y') }}</span>
                                <span class="mx-2 text-slate-300">•</span>
                                <span class="flex items-center gap-1"><i class="ti ti-clock"></i> {{ $blog->read_time }}</span>
                            </div>

                            <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('blogs.show', [$blog->slug]) }}">
                                    {{ $blog->name }}
                                </a>
                            </h3>

                            <p class="text-sm text-slate-600 line-clamp-3 mb-6 leading-relaxed">
                                {{ $blog->short_description }}
                            </p>

                            <div class="mt-auto pt-5 border-t border-slate-100 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img alt="Author" class="w-8 h-8 rounded-full object-cover bg-slate-100" src="https://ui-avatars.com/api/?name=Admin&background=random" />
                                    <span class="text-sm font-semibold text-slate-900">Admin</span>
                                </div>
                                <a class="text-blue-600 font-bold text-sm flex items-center gap-1 group-hover:gap-2 transition-all" href="{{ route('blogs.show' ,[ $blog->slug]) }}">
                                    Read <i class="ti ti-arrow-right text-base"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full py-12 text-center">
                        <i class="ti ti-notebook text-4xl text-slate-300 mb-3 block"></i>
                        <h3 class="text-lg font-medium text-slate-900">No articles found</h3>
                        <p class="text-slate-500 mt-1">Try adjusting your search or filters.</p>
                    </div>
                @endforelse

            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8">
            <div class="flex justify-center">
                {{ $blogs->withQueryString()->links('vendor.pagination.custom') }}
            </div>
        </section>

    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .mask-edges {
            mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
        }
    </style>
</x-app-layout>
