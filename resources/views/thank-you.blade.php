<x-app-layout>
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-16">

        <header class="text-center mb-16">
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-green-100 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-500" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                    <path d="M9 12l2 2l4 -4"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Order Successfully Placed!</h1>
            <p class="text-lg text-gray-500 max-w-lg mx-auto">
                Thank you for your purchase. A confirmation email with your digital access keys has been sent to your inbox.
            </p>
        </header>

        {{-- <div class="space-y-8 max-w-4xl mx-auto">

            <div class="bg-white border border-gray-200 rounded-2xl p-6 md:p-8 shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 divide-y md:divide-y-0 md:divide-x divide-gray-200">
                    <div class="flex flex-col pt-4 md:pt-0">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Order ID</span>
                        <span class="text-base font-bold text-gray-900">#GNS-98234</span>
                    </div>
                    <div class="flex flex-col pt-4 md:pt-0 md:pl-8">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Date</span>
                        <span class="text-base font-semibold text-gray-900">October 24, 2024</span>
                    </div>
                    <div class="flex flex-col pt-4 md:pt-0 md:pl-8">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Transection ID</span>
                        <span class="text-base font-semibold text-gray-900">October 24, 2024</span>
                    </div>
                </div>
            </div>

        </div> --}}

        <div class="mt-12 flex flex-col items-center gap-6">

            <a href="{{ route('home') }}" class="font-medium text-base text-blue-600 hover:text-blue-800 hover:underline underline-offset-4 flex items-center gap-1 transition-all" href="#">
                Continue Shopping
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M5 12l14 0"></path>
                    <path d="M13 18l6 -6"></path>
                    <path d="M13 6l6 6"></path>
                </svg>
            </a>
        </div>

        <section class="mt-16 pt-8 border-t border-gray-200 text-center max-w-4xl mx-auto">
            <div class="flex flex-col items-center gap-3">
                <p class="font-medium text-gray-500">
                    Need help with your order? Our engineering team is standing by.
                </p>
                <a class="font-semibold text-blue-600 flex items-center gap-2 hover:text-blue-800 transition-colors" href="mailto:support@gnosys.digital">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M4 14v-3a8 8 0 1 1 16 0v3"></path>
                        <path d="M18 19c0 1.657 -2.686 3 -6 3"></path>
                        <path d="M4 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z"></path>
                        <path d="M15 14a2 2 0 0 1 2 -2h1a2 2 0 0 1 2 2v3a2 2 0 0 1 -2 2h-1a2 2 0 0 1 -2 -2v-3z"></path>
                    </svg>
                    Contact technical support team
                </a>
            </div>
        </section>

    </section>
</x-app-layout>
