<footer class="bg-white border-t border-slate-200" aria-labelledby="footer-heading">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">

            <div class="space-y-6">
                <a href="{{ url('/') }}" class="inline-block">
                    <img src="{{ asset('assets/img/Ginosys-Digital-logo.webp') }}" alt="Gnosys Digital logo" class="h-10 w-auto">
                </a>

                <div class="space-y-4">
                    <div class="flex items-start gap-3 text-sm text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-blue-600 mt-0.5" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0" /></svg>
                        <p><strong>CA:</strong> 1664, 225 The East Mall, Toronto, ON, M9B 0A9</p>
                    </div>
                    <div class="flex items-start gap-3 text-sm text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-blue-600 mt-0.5"  width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0" /></svg>
                        <p><strong>UK:</strong> 20-22 Wenlock Road, London N1 7GU, UK</p>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-blue-600" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-phone"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                        <a href="tel:+16479479546" class="hover:text-blue-600 transition-colors">+1 647 947 9546</a>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="text-blue-600" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-mail"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" /><path d="M3 7l9 6l9 -6" /></svg>
                        <a href="mailto:connect@gnosysdigital.com" class="hover:text-blue-600 transition-colors">connect@gnosysdigital.com</a>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6">Digital Services</h3>
                <ul class="space-y-4">
                    @foreach(['ERPNext Implementation', 'AI Automation Data Services', 'SEO & Growth Services', 'Managed WordPress Services'] as $service)
                        <li>
                            <a href="#" class="text-sm text-slate-600 hover:text-blue-600 flex items-center gap-2 transition-all hover:translate-x-1">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" class="text-blue-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-right"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 6l6 6l-6 6" /></svg>
                                </span> {{ $service }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6">Quick Links</h3>
                <ul class="space-y-4">
                    <li>
                        <a href="#" class="text-sm text-slate-600 hover:text-blue-600 flex items-center gap-2 transition-all hover:translate-x-1">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" class="text-blue-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-right"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 6l6 6l-6 6" /></svg>
                            </span>
                            Explore Custom Development
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-sm text-slate-600 hover:text-blue-600 flex items-center gap-2 transition-all hover:translate-x-1">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" class="text-blue-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-right"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 6l6 6l-6 6" /></svg>
                            </span>
                            Explore eCommerce Solutions
                        </a>
                    </li>
                    <li>
                        <a href="#" class="text-sm text-slate-600 hover:text-blue-600 flex items-center gap-2 transition-all hover:translate-x-1">
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" class="text-blue-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevron-right"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M9 6l6 6l-6 6" /></svg>
                            </span>
                            Contact Us Today
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-6">Follow Us</h3>
                <div class="flex gap-3">
                    @foreach(['facebook', 'brand-x', 'brand-instagram', 'brand-pinterest', 'brand-linkedin'] as $social)
                        <a href="#" class="flex items-center justify-center w-10 h-10 rounded-lg bg-slate-100 text-slate-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm">
                            <i class="ti ti-{{ $social }} text-lg"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="bg-slate-900 py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-xs text-slate-400">
                Copyright &copy; 2018 &ndash; {{ date('Y') }} by Dwarkadhish E-Commerce Private Limited
            </p>
        </div>
    </div>
</footer>
