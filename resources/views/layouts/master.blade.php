<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ isset($moduleName) ? $moduleName . ' | ' : '' }}{{ config('app.name', 'ERP System') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Streamline your business with our advanced CRM template. Easily integrate and customize to manage sales, support, and customer interactions efficiently. Perfect for any business size">
    <meta name="keywords"
        content="Advanced CRM template, customer relationship management, business CRM, sales optimization, customer support software, CRM integration, customizable CRM, business tools, enterprise CRM solutions">
    <meta name="author" content="Dreams Technologies">
    <meta name="robots" content="index, follow">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon" href="{{ asset('assets/img/apple-icon.png') }}">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/theme-script.js') }}"></script>

    @vite(['resources/js/app.js'])

    @include('particles.css')

</head>

<body>

    <div class="main-wrapper">

        @include('particles.header')

        @include('particles.sidebar')

        <div class="page-wrapper">
            {{ $slot }}
        </div>
    </div>

    @include('admin.components.delete-modal')
    @include('particles.script')
    @stack('scripts')
</body>

</html>
