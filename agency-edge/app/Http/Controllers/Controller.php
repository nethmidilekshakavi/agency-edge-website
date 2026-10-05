<?php

namespace App\Http\Controllers;

use App\Support\SiteContent;
use Inertia\Inertia;
use Inertia\Response;

abstract class Controller
{
    /**
     * Render an Inertia page and pass SEO meta to the Blade root view, so
     * titles/descriptions/Open Graph tags exist in the server HTML (crawlers
     * and link previews see them without running JavaScript).
     */
    protected function page(string $component, array $props = [], ?string $title = null, ?string $description = null, ?string $image = null): Response
    {
        $meta = SiteContent::get('meta');

        return Inertia::render($component, $props)->withViewData([
            'seo' => [
                'title' => $title ? $title.' — '.($meta['site_name'] ?? 'Agency Edge') : ($meta['title_suffix'] ?? 'Agency Edge'),
                'description' => $description ?: ($meta['description'] ?? ''),
                'image' => $image ?: asset('images/og.jpg'),
                'url' => url()->current(),
            ],
        ]);
    }
}
