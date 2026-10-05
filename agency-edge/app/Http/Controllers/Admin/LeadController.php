<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): Response
    {
        $leads = Lead::query()
            ->when($request->string('q')->toString(), function ($q, $term) {
                $q->where(fn ($w) => $w->where('name', 'like', "%{$term}%")
                    ->orWhere('company', 'like', "%{$term}%")
                    ->orWhere('contact', 'like', "%{$term}%")
                    ->orWhere('message', 'like', "%{$term}%"));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Leads', [
            'leads' => $leads,
            'filters' => ['q' => $request->input('q', '')],
        ]);
    }

    public function update(Lead $lead): RedirectResponse
    {
        $lead->update(['read_at' => $lead->read_at ? null : now()]);

        return back();
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return back()->with('success', 'Enquiry deleted.');
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Name', 'Company', 'Email / phone', 'Goal', 'Message', 'Page']);
            Lead::latest()->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $l) {
                    fputcsv($out, [$l->created_at, $l->name, $l->company, $l->contact, $l->goal, $l->message, $l->source_page]);
                }
            });
            fclose($out);
        }, 'agency-edge-enquiries-'.now()->format('Y-m-d').'.csv', ['Content-Type' => 'text/csv']);
    }
}
