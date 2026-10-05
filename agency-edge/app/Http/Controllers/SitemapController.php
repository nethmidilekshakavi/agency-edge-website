<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = collect(['/', '/what-we-do', '/martech', '/ai-for-marketing', '/about', '/insights', '/contact'])
            ->map(fn ($path) => ['loc' => url($path), 'lastmod' => null]);

        Post::published()->get(['slug', 'updated_at'])->each(function (Post $post) use ($urls) {
            $urls->push(['loc' => url('/insights/'.$post->slug), 'lastmod' => $post->updated_at?->toAtomString()]);
        });

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $u) {
            $xml .= '<url><loc>'.e($u['loc']).'</loc>'.($u['lastmod'] ? '<lastmod>'.$u['lastmod'].'</lastmod>' : '').'</url>';
        }
        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }
}
