<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::published()->ordered()->with(['project', 'branch', 'equipment']);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        return view('videos.index', [
            'videos' => $query->paginate(12)->withQueryString(),
            'featuredVideos' => Video::published()->featured()->ordered()->take(4)->get(),
            'categories' => Video::CATEGORIES,
            'activeCategory' => $request->string('category')->toString(),
        ]);
    }
}
