<?php

namespace App\Support;

use App\Models\ContentBlock;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

/**
 * Website copy = config/site.php defaults, overridden per section by rows
 * in `content_blocks` (edited from Admin → Content).
 */
class SiteContent
{
    public const CACHE_KEY = 'site-content.v1';

    public static function defaults(): array
    {
        return config('site', []);
    }

    public static function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $content = self::defaults();

            try {
                if (! Schema::hasTable('content_blocks')) {
                    return $content;
                }
                foreach (ContentBlock::all() as $block) {
                    if (array_key_exists($block->key, $content) && is_array($block->value)) {
                        // Keep keys added to the defaults after the section was saved.
                        $content[$block->key] = array_replace($content[$block->key], $block->value);
                    }
                }
            } catch (Throwable) {
                // Database not ready (fresh install) — fall back to defaults.
            }

            return $content;
        });
    }

    public static function get(string $section): array
    {
        return self::all()[$section] ?? [];
    }

    /** Pick several sections for a page's props. */
    public static function only(string ...$sections): array
    {
        return array_intersect_key(self::all(), array_flip($sections));
    }

    public static function flush(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
