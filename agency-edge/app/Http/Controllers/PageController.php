<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\SiteContent;
use Inertia\Response;

class PageController extends Controller
{
    public function home(): Response
    {
        return $this->page('Home', [
            'content' => SiteContent::only(
                'hero', 'shift', 'intro', 'equation', 'disciplines', 'journey',
                'ai', 'demo', 'industries', 'founders', 'why', 'engagement', 'cta', 'martech'
            ),
            'posts' => Post::published()->latest('published_at')->take(3)->get()->map->toCard(),
        ]);
    }

    public function whatWeDo(): Response
    {
        return $this->page('WhatWeDo', [
            'content' => SiteContent::only('disciplines', 'digital', 'creative', 'process', 'engagement', 'journey', 'cta'),
        ], 'What We Do', 'Digital marketing, creative and brand, media and MarTech — one growth system built around a clear customer journey.');
    }

    public function martech(): Response
    {
        return $this->page('Martech', [
            'content' => SiteContent::only('martech', 'demo', 'process', 'cta'),
        ], 'MarTech Consulting', 'Strategy by Agency Edge. Technology by Ribelz. Marketing automation, AI customer engagement, CRM, analytics and integrations.');
    }

    public function ai(): Response
    {
        return $this->page('Ai', [
            'content' => SiteContent::only('ai', 'demo', 'cta'),
        ], 'AI for Marketing', 'Turn AI into a working part of your marketing team — AI customer assistants, lead management, AI-powered campaigns, automation and AI + CRM.');
    }

    public function about(): Response
    {
        return $this->page('About', [
            'content' => SiteContent::only('about', 'founders', 'why', 'industries', 'equation', 'cta'),
        ], 'About', 'A Marketing + Creative + Growth + MarTech Consulting company, led by Indika Jayapala and Sujith Caldera, with technology delivered by Ribelz.');
    }

    public function contact(): Response
    {
        return $this->page('Contact', [
            'content' => SiteContent::only('cta', 'engagement'),
        ], 'Contact', 'Ready to find your marketing edge? Tell us your challenge and start a conversation with Agency Edge.');
    }
}
