<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\SiteContent;
use Illuminate\Support\Str;
use Inertia\Response;

class InsightController extends Controller
{
    public function index(): Response
    {
        $posts = Post::published()->latest('published_at')->paginate(9)
            ->through(fn (Post $p) => $p->toCard());

        return $this->page('Insights/Index', [
            'content' => SiteContent::only('insights', 'cta'),
            'posts' => $posts,
        ], 'Insights', 'Thinking on marketing, MarTech and practical AI from Agency Edge.');
    }

    public function show(Post $post): Response
    {
        abort_unless($post->published_at && $post->published_at->isPast(), 404);

        $more = Post::published()->whereKeyNot($post->id)->latest('published_at')->take(2)->get()->map->toCard();

        return $this->page('Insights/Show', [
            'post' => [
                ...$post->toCard(),
                // Markdown → HTML. Raw HTML in posts is stripped for safety.
                'html' => Str::markdown($post->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]),
            ],
            'more' => $more,
            'content' => SiteContent::only('cta'),
        ], $post->title, $post->meta_description ?: $post->toCard()['excerpt'], $post->coverUrl());
    }
}
