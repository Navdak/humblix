<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentItem;
use App\Models\Project;
use App\Models\Video;
use Illuminate\Contracts\View\View;

class FoundationController extends Controller
{
    public function services(): View
    {
        abort_unless(auth()->user()?->canManage('services'), 403);

        $divisions = [
            'Humelix HVAC Installation',
            'Humelix Solar Installation',
            'Humelix Electrical & Maintenance',
            'Humelix Vendor / Equipment',
            'Home Appliance Installation',
        ];

        return view('admin.foundations.services', [
            'divisions' => $divisions,
            'projectCounts' => Project::selectRaw('service_division, COUNT(*) as aggregate')
                ->groupBy('service_division')
                ->pluck('aggregate', 'service_division'),
        ]);
    }

    public function safety(): View
    {
        abort_unless(auth()->user()?->canManage('safety'), 403);

        return view('admin.foundations.safety', [
            'safetyVideoCount' => Video::where('category', 'Safety')->count(),
            'publishedSafetyVideoCount' => Video::where('category', 'Safety')->where('status', 'published')->count(),
        ]);
    }

    public function seoSettings(): View
    {
        abort_unless(auth()->user()?->canManage('settings'), 403);

        return view('admin.foundations.seo-settings', [
            'publishedProjects' => Project::where('status', 'published')->count(),
            'publishedEquipment' => EquipmentItem::where('is_published', true)->count(),
            'publishedVideos' => Video::where('status', 'published')->count(),
        ]);
    }
}
