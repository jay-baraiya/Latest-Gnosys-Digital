<!DOCTYPE html>
<html lang="en" data-layout="{{ $appSettings->layout_size ?? 'default' }}"
    data-bs-theme="{{ $appSettings->theme_mode ?? 'light' }}"
    data-sidebar="{{ $appSettings->sidebar_color ?? 'light' }}"
    data-topbar="{{ $appSettings->topbar_color ?? 'light' }}" data-color="primary">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ isset($moduleName) ? $moduleName . ' | ' : '' }}{{ config('app.name', 'Gnosys Digital') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Streamline your business with our advanced CRM template. Easily integrate and customize to manage sales, support, and customer interactions efficiently. Perfect for any business size">
    <meta name="keywords"
        content="Advanced CRM template, customer relationship management, business CRM, sales optimization, customer support software, CRM integration, customizable CRM, business tools, enterprise CRM solutions">
    <meta name="author" content="Dreams Technologies">
    <meta name="robots" content="index, follow">

    <!-- Favicon -->
    <link rel="shortcut icon"
        href="{{ !empty($settings->favicon) ? asset($settings->favicon) : asset('assets/img/cropped-Ginosys-Digital-32x32.png') }}">

    <!-- Apple Icon -->
    <link rel="apple-touch-icon"
        href="{{ !empty($settings->favicon) ? asset($settings->favicon) : asset('assets/img/cropped-Ginosys-Digital-32x32.png') }}">

    <!-- Theme Config Js -->
    <script>
        sessionStorage.removeItem("__THEME_CONFIG__");
    </script>
    <script>
        window.appThemeSettings = {
            theme: "{{ $appSettings->theme_mode ?? 'light' }}",
            nav: "vertical",
            color: { color: "primary" },
            layout: { mode: "fluid" },
            topbar: { color: "{{ $appSettings->topbar_color ?? 'white' }}" },
            menu: { color: "{{ $appSettings->sidebar_color ?? 'light' }}" },
            sidenav: { size: "{{ $appSettings->layout_size ?? 'default' }}", user: !1 }
        };
    </script>
    @php
        // Default primary color set to #E41F07
        $hex = $appSettings->primary_color ?? '#E41F07';
        $hex = ltrim($hex, '#');

        if (strlen($hex) == 3) {
            $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
        } else {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        }
        $primaryRgb = "$r, $g, $b";

        // Hover color (15% darker)
        $hoverR = max(0, round($r * 0.85));
        $hoverG = max(0, round($g * 0.85));
        $hoverB = max(0, round($b * 0.85));
        $primaryHover = sprintf("#%02x%02x%02x", $hoverR, $hoverG, $hoverB);

        // Border color (10% darker)
        $borderR = max(0, round($r * 0.90));
        $borderG = max(0, round($g * 0.90));
        $borderB = max(0, round($b * 0.90));
        $primaryBorder = sprintf("#%02x%02x%02x", $borderR, $borderG, $borderB);

        // Light Background Color / Danger Transparent (Mixed with 90% White)
        $tintFactor = 0.80;
        $lightBgR = min(255, round($r + (255 - $r) * $tintFactor));
        $lightBgG = min(255, round($g + (255 - $g) * $tintFactor));
        $lightBgB = min(255, round($b + (255 - $b) * $tintFactor));
        $primaryLightBg = sprintf("#%02x%02x%02x", $lightBgR, $lightBgG, $lightBgB);

        // Text contrast (Black or White based on background)
        $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b);
        $textOnPrimary = $luminance > 128 ? '#333333' : '#ffffff';
    @endphp

    <style>
        :root {
            --primary:
                {{ $appSettings->primary_color ?? '#E41F07' }}
                !important;
            --primary-rgb:
                {{ $primaryRgb }}
                !important;
            --primary-hover:
                {{ $primaryHover }}
                !important;
            --primary-border:
                {{ $primaryBorder }}
                !important;
            --primary-transparent: rgba({{ $primaryRgb }}, 0.1) !important;

            /* Updated dynamic background color */
            --primary-light-bg:
                {{ $primaryLightBg }}
                !important;
            --danger-transparent:
                {{ $primaryLightBg }}
                !important;

            --text-on-primary:
                {{ $textOnPrimary }}
                !important;
        }
    </style>
    <script src="{{ asset('assets/js/theme-script.js') }}"></script>

    @vite(['resources/js/app.js'])

    @include('particles.css')

</head>

<body class="{{ (isset($appSettings->layout_size) && $appSettings->layout_size == 'mini') ? 'mini-sidebar' : '' }}">

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