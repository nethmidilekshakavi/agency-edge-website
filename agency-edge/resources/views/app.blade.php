@php
    $site = config('site.meta');
    $seo = $seo ?? [
        'title' => $site['title_suffix'] ?? config('app.name'),
        'description' => $site['description'] ?? '',
        'image' => asset('images/og.jpg'),
        'url' => url()->current(),
    ];
    $isAdmin = request()->is('admin*');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0a0a0b">
    <title inertia>{{ $seo['title'] }}</title>
    <meta name="description" content="{{ $seo['description'] }}">
    @if($isAdmin)
        <meta name="robots" content="noindex, nofollow">
    @else
        <link rel="canonical" href="{{ $seo['url'] }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $site['site_name'] ?? 'Agency Edge' }}">
        <meta property="og:title" content="{{ $seo['title'] }}">
        <meta property="og:description" content="{{ $seo['description'] }}">
        <meta property="og:url" content="{{ $seo['url'] }}">
        <meta property="og:image" content="{{ $seo['image'] }}">
        <meta name="twitter:card" content="summary_large_image">
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $site['site_name'] ?? 'Agency Edge',
            'url' => url('/'),
            'logo' => asset('favicon.svg'),
            'slogan' => 'Marketing Meets Technology.',
            'description' => $site['description'] ?? '',
            'founder' => [
                ['@type' => 'Person', 'name' => 'Indika Jayapala'],
                ['@type' => 'Person', 'name' => 'Sujith Caldera'],
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endif
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@500;600;700;800;900&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/js/app.js'])
    @inertiaHead
</head>
<body class="{{ $isAdmin ? 'is-admin' : 'is-site' }}">
    <noscript>
        <div style="padding:2rem;font-family:sans-serif;color:#fff;background:#0a0a0b">
            <h1>{{ $seo['title'] }}</h1>
            <p>{{ $seo['description'] }}</p>
        </div>
    </noscript>
    @inertia
</body>
</html>
