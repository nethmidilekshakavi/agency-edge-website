<?php

namespace App\Http\Middleware;

use App\Support\SiteContent;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $content = SiteContent::all();

        return [
            ...parent::share($request),
            'site' => [
                'meta' => $content['meta'] ?? [],
                'contact' => $content['contact'] ?? [],
                'newsletter' => $content['newsletter'] ?? [],
                'cta' => $content['cta'] ?? [],
                'year' => (int) date('Y'),
            ],
            'auth' => [
                'user' => $request->user()?->only('id', 'name', 'email'),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'newsletter' => fn () => $request->session()->get('newsletter'),
            ],
        ];
    }
}
