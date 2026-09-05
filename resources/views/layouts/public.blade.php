<!doctype html><html lang="{{ app()->getLocale() }}"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $settings['seo_title']->displayValue() ?? 'AUTO MOTORS SARL' }}</title>
<meta name="description" content="{{ $settings['seo_description']->displayValue() ?? '' }}">
@php
$heroPath = $settings['hero_image']->value ?? 'images/hero-automotive.png';
$heroUrl = str_starts_with($heroPath, 'uploads/') ? asset('storage/'.$heroPath) : asset($heroPath);
@endphp
<link rel="preload" as="image" href="{{ $heroUrl }}" fetchpriority="high">
<link rel="canonical" href="{{ url('/') }}"><meta property="og:type" content="website"><meta property="og:title" content="{{ $settings['seo_title']->displayValue() ?? '' }}"><meta property="og:description" content="{{ $settings['seo_description']->displayValue() ?? '' }}"><meta property="og:image" content="{{ $heroUrl }}">
@vite(['resources/css/app.css','resources/js/app.js'])
</head><body>
<a class="skip-link" href="#main">{{ __('messages.skip') }}</a>
@yield('content')
</body></html>
