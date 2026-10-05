<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/** Image uploads for content fields such as founder photos. */
class UploadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate(['file' => ['required', 'image', 'max:4096']]);

        $path = $request->file('file')->store('uploads', 'public');

        return response()->json(['url' => Storage::disk('public')->url($path)]);
    }
}
