<x-app-layout>


    <div class="pt-24 sm:pt-32 pb-16 bg-slate-50 min-h-screen">
        <article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- SEO Optimized Breadcrumbs --}}
            <nav aria-label="Breadcrumb" class="mb-4 sm:mb-6">
                <ol itemscope itemtype="https://schema.org/BreadcrumbList" class="flex items-center space-x-1 sm:space-x-2 text-xs sm:text-sm text-slate-500 flex-wrap sm:flex-nowrap">
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <a itemprop="item" href="{{ url('/') }}" class="flex items-center hover:text-blue-600 transition-colors font-medium">
                            <i class="ti ti-home mr-1 sm:mr-1.5 text-base sm:text-lg"></i>
                            <span itemprop="name">Home</span>
                        </a>
                        <meta itemprop="position" content="1" />
                    </li>
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <div class="flex items-center">
                            <i class="ti ti-chevron-right text-slate-400 mx-0.5 sm:mx-1 text-sm sm:text-base"></i>
                            <a itemprop="item" href="{{ route('blogs.listing') }}" class="hover:text-blue-600 transition-colors whitespace-nowrap font-medium">
                                <span itemprop="name">The Journal</span>
                            </a>
                        </div>
                        <meta itemprop="position" content="2" />
                    </li>
                    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                        <div class="flex items-center">
                            <i class="ti ti-chevron-right text-slate-400 mx-0.5 sm:mx-1 text-sm sm:text-base"></i>
                            <span itemprop="name" class="text-slate-900 font-semibold truncate max-w-[120px] sm:max-w-md" aria-current="page">
                                {{ $blog->name }}
                            </span>
                        </div>
                        <meta itemprop="position" content="3" />
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start">

                {{-- Left Side: Hero Image --}}
                <div class="lg:col-span-7 relative lg:sticky lg:top-32">
                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 p-2 sm:p-4">
                        <div class="relative w-full aspect-[16/10] bg-slate-100 rounded-xl overflow-hidden group">
                            <img alt="{{ $blog->name }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                src="{{ $blog->image }}" />
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/10 to-transparent pointer-events-none"></div>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Blog Meta Details --}}
                <div class="lg:col-span-5 flex flex-col">

                    <div class="mb-4 sm:mb-6">
                        <div class="flex items-center gap-3 mb-3 sm:mb-4">
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2.5 py-1 text-[10px] sm:text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                {{ $blog->category->name ?? 'Insights' }}
                            </span>
                            <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                <i class="ti ti-clock"></i> {{ $blog->read_time }}
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight mb-4">
                            {{ $blog->name }}
                        </h1>

                        <div class="flex items-center gap-3 mt-4">
                            <img alt="Author" class="w-10 h-10 rounded-full object-cover bg-slate-200" src="https://ui-avatars.com/api/?name=Admin&background=f1f5f9&color=0f172a" />
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Admin</p>
                                <p class="text-xs text-slate-500">
                                    Published on <time datetime="{{ \Carbon\Carbon::parse($blog->datetime)->toDateString() }}">{{ \Carbon\Carbon::parse($blog->datetime)->format('M d, Y') }}</time>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 p-5 sm:p-8 mb-6 sm:mb-8">

                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3">Overview</h3>
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed italic">
                            "{{ $blog->short_description }}"
                        </p>

                        <hr class="border-slate-100 my-6">

                        {{-- Tags & Social Share --}}
                        <div class="space-y-5">
                            @if($blog->tags)
                            <div class="flex items-start sm:items-center gap-2 sm:gap-3 flex-col sm:flex-row">
                                <span class="font-bold text-slate-900 uppercase tracking-wider text-[10px] sm:text-xs mt-1 sm:mt-0">Tags:</span>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(explode(',', $blog->tags) as $tag)
                                        <a href="{{ route('blogs.listing', ['tag' => trim($tag)]) }}" class="text-[10px] sm:text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-blue-50 hover:text-blue-700 px-3 py-1.5 rounded-full transition-colors border border-slate-200/60">
                                            {{ trim($tag) }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <div class="flex items-center gap-3 pt-3">
                                <span class="font-bold text-slate-900 uppercase tracking-wider text-[10px] sm:text-xs">Share:</span>
                                <div class="flex gap-2">
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($blog->name) }}" target="_blank" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-500 hover:bg-[#0A66C2] hover:text-white hover:border-[#0A66C2] transition-all">
                                        <i class="ti ti-brand-linkedin text-base sm:text-lg"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($blog->name) }}" target="_blank" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-500 hover:bg-black hover:text-white hover:border-black transition-all">
                                        <i class="ti ti-brand-x text-base sm:text-lg"></i>
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-500 hover:bg-[#1877F2] hover:text-white hover:border-[#1877F2] transition-all">
                                        <i class="ti ti-brand-facebook text-base sm:text-lg"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Rich Text Content --}}
            <div class="mt-12 sm:mt-16 bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-10 lg:p-16">
                <div class="prose prose-sm sm:prose-base lg:prose-lg prose-slate prose-a:text-blue-600 hover:prose-a:text-blue-800 prose-img:rounded-xl max-w-4xl mx-auto">
                    {!! html_entity_decode($blog->description) !!}
                </div>
            </div>

        </article>

        {{-- Related Articles Section --}}
        @if($relatedBlogs->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 sm:mt-20 pt-10 border-t border-slate-200">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900">Read Next</h2>
                <a href="{{ route('blogs.listing', ['category' => $blog->category->slug ?? '']) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors">
                    View All {{ $blog->category->name ?? '' }} →
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedBlogs as $related)
                    <article class="group flex flex-col bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                        <div class="aspect-[16/10] relative overflow-hidden bg-slate-100">
                            <a href="{{ route('blogs.show', $related->slug) }}">
                                <img alt="{{ $related->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-out"
                                    src="{{ $related->image }}" />
                            </a>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="flex items-center text-[10px] text-slate-500 mb-2 font-semibold tracking-wide">
                                <span>{{ \Carbon\Carbon::parse($related->datetime)->format('M d, Y') }}</span>
                            </div>
                            <h3 class="text-base font-bold text-slate-900 mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                                <a href="{{ route('blogs.show', $related->slug) }}">
                                    {{ $related->name }}
                                </a>
                            </h3>
                            <div class="mt-auto pt-4 flex items-center justify-between border-t border-slate-50">
                                <span class="text-xs text-slate-500 font-medium"><i class="ti ti-clock"></i> {{ $related->read_time }}</span>
                                <a class="text-blue-600 font-bold text-xs flex items-center gap-1 group-hover:gap-1.5 transition-all" href="{{ route('blogs.show', $related->slug) }}">
                                    Read <i class="ti ti-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</x-app-layout>
