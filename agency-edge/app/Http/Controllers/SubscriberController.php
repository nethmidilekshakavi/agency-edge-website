<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Support\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        if (filled($request->input('website'))) {
            return back();
        }

        $data = $request->validateWithBag('newsletter', [
            'email' => ['required', 'email:rfc', 'max:190'],
        ]);

        Subscriber::updateOrCreate(
            ['email' => strtolower($data['email'])],
            ['unsubscribed_at' => null],
        );

        return back()->with('newsletter', SiteContent::get('newsletter')['success'] ?? "You're on the list.");
    }
}
