<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Posts/Index', [
            'posts' => Post::latest()->paginate(20)->through(fn (Post $p) => [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'category' => $p->category,
                'published_at' => $p->published_at?->format('j M Y'),
                'is_live' => $p->published_at && $p->published_at->isPast(),
            ]),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Posts/Edit', ['post' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $post = Post::create($this->validated($request));

        return redirect("/admin/posts/{$post->id}/edit")->with('success', 'Article created.');
    }

    public function edit(Post $post): Response
    {
        return Inertia::render('Admin/Posts/Edit', [
            'post' => [
                ...$post->only('id', 'title', 'slug', 'category', 'excerpt', 'body', 'meta_description'),
                'cover_url' => $post->coverUrl(),
                'published_at' => $post->published_at?->format('Y-m-d\TH:i'),
            ],
        ]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $post->update($this->validated($request, $post));

        return back()->with('success', 'Article saved.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->cover_image && ! Str::startsWith($post->cover_image, ['http', '/'])) {
            Storage::disk('public')->delete($post->cover_image);
        }
        $post->delete();

        return redirect('/admin/posts')->with('success', 'Article deleted.');
    }

    private function validated(Request $request, ?Post $post = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('posts', 'slug')->ignore($post?->id)],
            'category' => ['nullable', 'string', 'max:80'],
            'excerpt' => ['nullable', 'string', 'max:400'],
            'body' => ['required', 'string'],
            'meta_description' => ['nullable', 'string', 'max:300'],
            'published_at' => ['nullable', 'date'],
            'cover' => ['nullable', 'image', 'max:4096'],
            'remove_cover' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('cover')) {
            $data['cover_image'] = $request->file('cover')->store('posts', 'public');
        } elseif ($request->boolean('remove_cover')) {
            $data['cover_image'] = null;
        }

        unset($data['cover'], $data['remove_cover']);

        return $data;
    }
}
