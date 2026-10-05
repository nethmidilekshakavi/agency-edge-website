<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'category', 'excerpt', 'body',
        'cover_image', 'meta_description', 'published_at',
    ];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            $post->slug = Str::slug($post->slug ?: $post->title);
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function coverUrl(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        return Str::startsWith($this->cover_image, ['http://', 'https://', '/'])
            ? $this->cover_image
            : Storage::disk('public')->url($this->cover_image);
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags($this->body)) / 220));
    }

    /** Shape used by the public pages. */
    public function toCard(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'excerpt' => $this->excerpt ?: Str::limit(strip_tags(Str::markdown($this->body)), 180),
            'cover' => $this->coverUrl(),
            'date' => $this->published_at?->format('j M Y'),
            'minutes' => $this->readingMinutes(),
        ];
    }
}
