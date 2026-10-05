<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Post;
use App\Models\Subscriber;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'leads' => Lead::count(),
                'unread' => Lead::whereNull('read_at')->count(),
                'subscribers' => Subscriber::whereNull('unsubscribed_at')->count(),
                'posts' => Post::published()->count(),
                'drafts' => Post::whereNull('published_at')->count(),
            ],
            'recentLeads' => Lead::latest()->take(5)->get(['id', 'name', 'company', 'goal', 'read_at', 'created_at']),
        ]);
    }
}
