<x-app-layout>
    <section class="relative pt-32 pb-20 px-6 md:px-12 lg:px-24 bg-light-gradient overflow-hidden" id="hero">
        <div class="hero-bg-overlay absolute inset-0 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <div data-purpose="hero-content">
                <span
                    class="inline-block py-1 px-3 bg-blue-100 text-blue-600 text-xs font-bold tracking-wider rounded uppercase mb-6">
                    FREE WEEKLY BUSINESS INSIGHTS
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
                    Build Smarter Systems. Scale Faster
                </h1>
                <p class="text-lg text-slate-600 mb-8 max-w-lg">
                    Get practical insights on ERPNext, AI automation, digital growth, and business operations delivered directly to your inbox every week.
                </p>

                <ul class="space-y-3 mb-10">
                    <li class="flex items-center gap-3 text-slate-700 font-medium">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path clip-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                fill-rule="evenodd"></path>
                        </svg>
                        ERPNext implementation tips & best practices
                    </li>
                    <li class="flex items-center gap-3 text-slate-700 font-medium">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path clip-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                fill-rule="evenodd"></path>
                        </svg>
                        AI automation workflows that save time
                    </li>
                    <li class="flex items-center gap-3 text-slate-700 font-medium">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path clip-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                fill-rule="evenodd"></path>
                        </svg>
                        Growth strategies backed by real-world results
                    </li>
                    <li class="flex items-center gap-3 text-slate-700 font-medium">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path clip-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                fill-rule="evenodd"></path>
                        </svg>
                        Business systems, operations, and productivity insights
                    </li>
                </ul>

                <div class="flex flex-col gap-4 mb-8">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input
                            class="flex-1 px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-slate-700"
                            placeholder="First Name (Optional)" type="text" />
                        <input
                            class="flex-1 px-4 py-3 rounded-lg border border-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-slate-700"
                            placeholder="Business Email Address*" required="" type="email" />
                    </div>

                    <button
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-colors whitespace-nowrap shadow-lg shadow-blue-200 sm:w-fit w-full">
                        Subscribe Free
                    </button>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex -space-x-3 shrink-0">
                        <img alt="User 1"
                            class="relative z-40 w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBXT2GGxrEJq4TeZH0cxoZEm3nXeRwxDtat66wQmZfqnhVEDQi_wmH-v2mGBEXM8OV0ruI2nleSyYsGCH5B2GuzxOicmqdJUce3BmZ2WlSLgm5cBMB3PlfCwGCOjh9TyG9ng0EWaCCH2GBeZgmmwYn0BDhetQEsNSDcljboRnWAYGnfWtGxGBjU10pkxwz3InW-ftuQe3c0HTEGtdehr9aR1SefLr91IM9YpVfudGndDPQ86IKYnsuKHvyGb-T274pBs_zeaatkpuIT" />
                        <img alt="User 2"
                            class="relative z-30 w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDtv2XBW-YDg2qYZHywJSk0SvCPyjZAlPejCwR0_-o50QB4psVcKyJ44UTK-XPV8eOwSWhLxZPoUz4S9RqrXIorZlsv46dAaagXf6cOGrYTzHnKo2x-tXXxfG61kF9L1Ds3YCrkwF5iy-zEiY3Kt4i-41Cy6zFEg1yWSc8LAvJ8ANNpimROUDkCzVOyBHovLth1mfM7JVoiBTVIYIS1hcikr9D_TWW_Sqz7-UxabIAYjHbP3QC7Hm-qMIRDE3JZS6hboK-fP9IQn7JL" />
                        <img alt="User 3"
                            class="relative z-20 w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuCNAXJXzbWUdkwaijS32CrEdYiBfFeoG6BzKhry_Ehgn5Vsp6V3mYnPk-NaTV_Al_ct-VGIGIyJNw00A37Uvn7De-f_BTkOrXIA-ZUKRcdfPQ4m7eF-t2Sf1H6EdztjsqSbS_QZJ8OwNElMqgMI7m-B54C3-VN9Dbn4khyeBxTkIHXauqkpQOIFlaVywfh6uj5TmOLz9nDco7l7fyB6xTdr75gjbu2HBYx3GpyXikiGUDQCwqHy_28qlVrRM0vDBHbibTBYXMOjPlx3" />
                        <img alt="User 4"
                            class="relative z-10 w-10 h-10 rounded-full border-2 border-white object-cover shadow-sm"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuDwmsc8wrjQ75tnAqak4kqHp9r_JM9BWYs_5-oleSE0uT3XHcyLEPmvnnPQ_t_pQIBCSCZ_KNjkjO2BxbLk43-H4BXg0iSGvkRel_k66VbTYhDNdX_a0EqhzgnVa0mU0fEc7yGjK6UZhVao03qHa4iCdqb-KWMqvhzmoDSa38fxiGrbtxCuYJMvgjToo7EYP2ha14Ou2Fat0ddmPUwa-2fPOog6lAAyBmuw3RI7bs7UjIvQ4n_rSBr9SeRF_TjTYEezEKFn89nYk3hk" />
                    </div>

                    <p class="text-sm text-slate-500 leading-snug">
                        Join business owners, operations leaders, and growth-focused teams receiving weekly insights from <span class="font-bold text-slate-800">Gnosys Digital</span>.
                    </p>
                </div>
            </div>
            <div class="relative" data-purpose="hero-visual">
                <div class="relative z-10">
                    <img alt="Newsletter Growth Insights" class="w-full h-auto drop-shadow-2xl rounded-xl"
                        src="{{ asset('assets/img/mail-champ-banner.webp') }}" />
                </div>
            </div>

        </div>
    </section>

    <section class="py-20 px-6 md:px-12 lg:px-24">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2">Why Professionals Subscribe</h2>
            <div class="w-12 h-1 bg-blue-600 mx-auto mb-16 rounded-full"></div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow text-center"
                    data-purpose="benefit-card">
                    <div
                        class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2" />
                            <circle cx="12" cy="12" r="6" stroke-width="2" />
                            <path d="M12 12V2M12 2L9 5M12 2L15 5" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Actionable</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Practical frameworks, templates, and strategies you can implement immediately.
                    </p>
                </div>
                <div class="bg-white p-10 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow text-center"
                    data-purpose="benefit-card">
                    <div
                        class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M2 9l10-7 10 7-10 7L2 9zm0 0v6l10 7 10-7V9M12 2v14M6 5.5l12 0M2 9h20M12 16h0"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Expert-Curated</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Insights from ERP consultants, automation specialists, and growth experts.
                    </p>
                </div>
                <div class="bg-white p-10 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow text-center"
                    data-purpose="benefit-card">
                    <div
                        class="w-16 h-16 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">No Fluff</h3>
                    <p class="text-slate-600 text-sm leading-relaxed">
                        Straightforward advice focused on improving efficiency, growth, and profitability.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 md:px-12 lg:px-24 bg-slate-50">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-2">What You'll Learn Every Week</h2>
            <div class="w-12 h-1 bg-blue-600 mx-auto mb-16 rounded-full"></div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100 text-left group"
                    data-purpose="feature-card">
                    <div
                        class="h-48 bg-gradient-to-b from-[#F4F7FF] to-white flex items-center justify-center p-6 border-b border-slate-50">
                        <svg class="w-full h-full transform group-hover:scale-105 transition-transform duration-500"
                            viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="110" y="25" width="35" height="20" rx="4" fill="#DBEAFE" />
                            <rect x="150" y="50" width="35" height="20" rx="4" fill="#BFDBFE" />
                            <rect x="110" y="75" width="35" height="20" rx="4" fill="#93C5FD" />
                            <path d="M127 45 v5 h40 v5" stroke="#93C5FD" stroke-width="2" stroke-linejoin="round" />
                            <path d="M127 75 v-5 h40 v-5" stroke="#60A5FA" stroke-width="2"
                                stroke-linejoin="round" />
                            <circle cx="127" cy="50" r="3" fill="#3B82F6" />
                            <rect x="35" y="35" width="45" height="35" rx="8" fill="#3B82F6" />
                            <path d="M45 25 v10 M70 25 v10" stroke="#93C5FD" stroke-width="3"
                                stroke-linecap="round" />
                            <circle cx="45" cy="22" r="3" fill="#60A5FA" />
                            <circle cx="70" cy="22" r="3" fill="#60A5FA" />
                            <circle cx="48" cy="48" r="4" fill="white" />
                            <circle cx="67" cy="48" r="4" fill="white" />
                            <path d="M52 58 h11" stroke="white" stroke-width="2.5" stroke-linecap="round" />
                            <path d="M80 55 h30" stroke="#93C5FD" stroke-width="2" stroke-dasharray="4 4" />
                        </svg>
                    </div>
                    <div class="p-8 pt-4">
                        <h3 class="text-xl font-bold mb-3 text-slate-900">ERPNext & AI Automation</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Implementation tips, workflow automation ideas, and process optimization strategies.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100 text-left group"
                    data-purpose="feature-card">
                    <div
                        class="h-48 bg-gradient-to-b from-[#F4F7FF] to-white flex items-center justify-center p-6 border-b border-slate-50">
                        <svg class="w-full h-full transform group-hover:scale-105 transition-transform duration-500"
                            viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="30" y="25" width="140" height="75" rx="8" fill="white"
                                stroke="#E0E7FF" stroke-width="3" />
                            <path d="M30 42 h140" stroke="#E0E7FF" stroke-width="2" />
                            <circle cx="42" cy="33.5" r="2.5" fill="#BFDBFE" />
                            <circle cx="50" cy="33.5" r="2.5" fill="#93C5FD" />
                            <circle cx="58" cy="33.5" r="2.5" fill="#60A5FA" />
                            <rect x="40" y="55" width="30" height="4" rx="2" fill="#E0E7FF" />
                            <rect x="40" y="65" width="20" height="4" rx="2" fill="#E0E7FF" />
                            <rect x="85" y="75" width="14" height="10" rx="3" fill="#DBEAFE" />
                            <rect x="105" y="65" width="14" height="20" rx="3" fill="#BFDBFE" />
                            <rect x="125" y="55" width="14" height="30" rx="3" fill="#60A5FA" />
                            <rect x="145" y="40" width="14" height="45" rx="3" fill="#3B82F6" />
                        </svg>
                    </div>
                    <div class="p-8 pt-4">
                        <h3 class="text-xl font-bold mb-3 text-slate-900">Growth & Marketing Systems</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            SEO, lead generation, analytics, and scalable marketing frameworks.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-slate-100 text-left group"
                    data-purpose="feature-card">
                    <div
                        class="h-48 bg-gradient-to-b from-[#F4F7FF] to-white flex items-center justify-center p-6 border-b border-slate-50">
                        <svg class="w-full h-full transform group-hover:scale-105 transition-transform duration-500"
                            viewBox="0 0 200 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="65" cy="65" r="22" fill="#DBEAFE" />
                            <circle cx="65" cy="65" r="8" fill="white" />
                            <path d="M65 35 v12 M65 83 v12 M35 65 h12 M83 65 h12" stroke="#DBEAFE" stroke-width="7"
                                stroke-linecap="round" />
                            <path d="M44 44 l8 8 M78 78 l8 8 M44 86 l8 -8 M78 42 l8 -8" stroke="#DBEAFE"
                                stroke-width="7" stroke-linecap="round" />

                            <circle cx="140" cy="80" r="14" fill="#EFF6FF" />
                            <circle cx="140" cy="80" r="5" fill="white" />
                            <path d="M140 60 v8 M140 92 v8 M120 80 h8 M152 80 h8" stroke="#EFF6FF" stroke-width="5"
                                stroke-linecap="round" />
                            <path d="M125 65 l6 6 M149 89 l6 6 M125 95 l6 -6 M149 65 l6 -6" stroke="#EFF6FF"
                                stroke-width="5" stroke-linecap="round" />

                            <rect x="90" y="25" width="70" height="55" rx="6" fill="white"
                                stroke="#E0E7FF" stroke-width="3" filter="drop-shadow(0 4px 6px rgba(0,0,0,0.02))" />
                            <circle cx="103" cy="40" r="5" fill="#3B82F6" />
                            <path d="M100.5 40 l1.5 1.5 l3 -3" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <rect x="115" y="38" width="30" height="4" rx="2" fill="#93C5FD" />
                            <circle cx="103" cy="55" r="5" fill="#3B82F6" />
                            <path d="M100.5 55 l1.5 1.5 l3 -3" stroke="white" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <rect x="115" y="53" width="20" height="4" rx="2" fill="#93C5FD" />
                            <circle cx="103" cy="70" r="5" fill="#E0E7FF" />
                            <rect x="115" y="68" width="25" height="4" rx="2" fill="#E0E7FF" />
                        </svg>
                    </div>
                    <div class="p-8 pt-4">
                        <h3 class="text-xl font-bold mb-3 text-slate-900">Business Operations & Productivity</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Systems, tools, and operational improvements that help teams perform better.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="py-24 px-6 md:px-12 lg:px-24 bg-white">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16">
            <div class="md:w-1/2">
                <h2 class="text-3xl font-extrabold text-slate-900 leading-tight mb-6">
                    Built for Growing Businesses and Ambitious Teams
                </h2>
                <p class="text-lg text-slate-600 leading-relaxed">
                    Whether you're running a manufacturing company, eCommerce brand, service business, educational institution, or startup, you'll receive practical insights designed to improve efficiency, visibility, and growth.
                </p>
            </div>
            <div class="md:w-1/2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="flex items-center gap-3 font-medium text-slate-800">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewbox="0 0 20 20">
                        <path clip-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            fill-rule="evenodd"></path>
                    </svg>
                    ERPNext Implementation
                </div>
                <div class="flex items-center gap-3 font-medium text-slate-800">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewbox="0 0 20 20">
                        <path clip-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            fill-rule="evenodd"></path>
                    </svg>
                    AI Automation
                </div>
                <div class="flex items-center gap-3 font-medium text-slate-800">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewbox="0 0 20 20">
                        <path clip-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            fill-rule="evenodd"></path>
                    </svg>
                    Lead Generation
                </div>
                <div class="flex items-center gap-3 font-medium text-slate-800">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewbox="0 0 20 20">
                        <path clip-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            fill-rule="evenodd"></path>
                    </svg>
                    Business Operations
                </div>
                <div class="flex items-center gap-3 font-medium text-slate-800">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewbox="0 0 20 20">
                        <path clip-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            fill-rule="evenodd"></path>
                    </svg>
                    SEO & Marketing
                </div>
                <div class="flex items-center gap-3 font-medium text-slate-800">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewbox="0 0 20 20">
                        <path clip-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            fill-rule="evenodd"></path>
                    </svg>
                    Digital Transformation
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 md:px-12 lg:px-24 bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-12">
            <div class="lg:w-1/2">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Turn Business Complexity Into Scalable Growth</h2>
                <p class="text-slate-400 text-lg">
                    This aligns much better with your ERPNext, automation, and digital transformation positioning and differentiates you from generic marketing newsletters.
                </p>
            </div>
            <div class="lg:w-1/2 w-full">
                <form class="flex flex-col sm:flex-row gap-0 sm:overflow-hidden rounded-lg">
                    <input class="bg-white text-slate-800 px-4 py-4 focus:outline-none flex-1"
                        placeholder="Business Email Address*" required="" type="email" />
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-4 transition-colors">
                        Subscribe Free
                    </button>
                </form>
                <div class="flex flex-wrap items-center gap-8 mt-6 text-sm text-slate-400">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                            viewbox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke-linecap="round"
                                stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        No spam
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                            viewbox="0 0 24 24">
                            <path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        Unsubscribe anytime
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                            viewbox="0 0 24 24">
                            <path
                                d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path>
                        </svg>
                        100% Free
                    </span>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
