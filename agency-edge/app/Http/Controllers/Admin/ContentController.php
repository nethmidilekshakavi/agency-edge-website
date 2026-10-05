<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Support\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContentController extends Controller
{
    public function index(): Response
    {
        $custom = ContentBlock::pluck('updated_at', 'key');

        $sections = collect(SiteContent::defaults())->keys()->map(fn ($key) => [
            'key' => $key,
            'customised' => $custom->has($key),
            'updated_at' => $custom->get($key)?->diffForHumans(),
        ]);

        return Inertia::render('Admin/Content/Index', ['sections' => $sections]);
    }

    public function edit(string $section): Response
    {
        $defaults = SiteContent::defaults();
        abort_unless(array_key_exists($section, $defaults), 404);

        return Inertia::render('Admin/Content/Edit', [
            'section' => $section,
            'value' => SiteContent::get($section),
            'defaults' => $defaults[$section],
            'customised' => ContentBlock::where('key', $section)->exists(),
        ]);
    }

    public function update(Request $request, string $section): RedirectResponse
    {
        $defaults = SiteContent::defaults();
        abort_unless(array_key_exists($section, $defaults), 404);

        $request->validate(['value' => ['required', 'array']]);

        // Only keep keys that exist in the defaults, so the page code can rely on its shape.
        $value = array_intersect_key($request->input('value'), $defaults[$section]);
        $value = $this->clean($value);

        ContentBlock::updateOrCreate(['key' => $section], ['value' => $value]);
        SiteContent::flush();

        return back()->with('success', 'Saved. The live site is updated.');
    }

    public function destroy(string $section): RedirectResponse
    {
        ContentBlock::where('key', $section)->delete();
        SiteContent::flush();

        return back()->with('success', 'Section reset to the default copy.');
    }

    /** Trim strings, drop empty list rows, cap length. */
    private function clean(mixed $value): mixed
    {
        if (is_array($value)) {
            $isList = array_is_list($value);
            $out = [];
            foreach ($value as $k => $v) {
                $v = $this->clean($v);
                if ($isList && ($v === '' || $v === [] || $v === null)) {
                    continue;
                }
                $out[$k] = $v;
            }

            return $isList ? array_values($out) : $out;
        }

        return is_string($value) ? mb_substr(trim($value), 0, 5000) : (is_scalar($value) ? (string) $value : '');
    }
}
