<?php

namespace App\Http\Controllers;

use App\Mail\NewLeadMail;
use App\Models\Lead;
use App\Support\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class LeadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // Honeypot: real people never fill the hidden "website" field.
        if (filled($request->input('website'))) {
            return back()->with('success', SiteContent::get('cta')['success'] ?? 'Thank you.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:160'],
            'contact' => ['required', 'string', 'max:160', function ($attr, $value, $fail) {
                $isEmail = filter_var($value, FILTER_VALIDATE_EMAIL);
                $isPhone = preg_match('/^\+?[0-9\s\-().]{7,20}$/', $value);
                if (! $isEmail && ! $isPhone) {
                    $fail('Please enter a valid email address or phone number.');
                }
            }],
            'goal' => ['nullable', 'string', 'max:120'],
            'message' => ['nullable', 'string', 'max:5000'],
            'source_page' => ['nullable', 'string', 'max:255'],
        ], [], ['contact' => 'email or phone']);

        $lead = Lead::create([...$data, 'ip_address' => $request->ip()]);

        if ($to = config('agency.leads_email')) {
            try {
                Mail::to($to)->send(new NewLeadMail($lead));
            } catch (Throwable $e) {
                // The lead is saved either way; a mail outage must not lose it.
                Log::error('Lead email failed: '.$e->getMessage(), ['lead_id' => $lead->id]);
            }
        }

        return back()->with('success', SiteContent::get('cta')['success'] ?? 'Thank you.');
    }
}
