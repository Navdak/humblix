<?php
namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\EquipmentItem;
use App\Models\JobOpening;
use App\Models\Project;
use App\Models\Review;
use App\Models\TeamMember;
use App\Models\Video;
use App\Support\UchContent;
use Illuminate\Support\Arr;

class PageController extends Controller
{
    public function about() { return view('pages.about'); }
    public function founder() { return view('pages.founder'); }
    public function industries()
    {
        return view('industries.index', [
            'industries' => UchContent::industries(),
            'services' => UchContent::industryServices(),
        ]);
    }

    public function industry(string $slug)
    {
        $industry = Arr::first(UchContent::industries(), fn ($industry) => $industry['slug'] === $slug);
        abort_if(! $industry, 404);

        return view('industries.show', [
            'industry' => $industry,
            'services' => UchContent::industryServices(),
            'projectFallbackImages' => UchContent::projectFallbackImages(),
            'projects' => Project::where('status','published')->where('sector',$industry['title'])->latest()->take(6)->get(),
        ]);
    }
    public function sector(string $slug)
    {
        $sector = Arr::first(UchContent::sectors(), fn($sector) => $sector['slug'] === $slug);
        abort_if(! $sector, 404);
        return view('sectors.show', [
            'sector' => $sector,
            'projects' => Project::where('status','published')->where('sector',$sector['title'])->latest()->get(),
        ]);
    }
    public function team()
    {
        return view('team.index', [
            'members' => TeamMember::where('is_visible',true)->orderBy('sort_order')->get(),
            'cultureImages' => UchContent::cultureImages(),
        ]);
    }
    public function safety()
    {
        return view('pages.safety', [
            'pillars' => UchContent::safetyPillars(),
            'modules' => UchContent::safetyModules(),
            'process' => UchContent::safetyProcess(),
            'divisionSafety' => UchContent::safetyAcrossDivisions(),
            'safetyVideos' => Video::published()->where('category', 'Safety')->ordered()->take(3)->get(),
        ]);
    }

    public function safetyTopic(string $slug)
    {
        $topic = Arr::first(UchContent::safetyModules(), fn ($topic) => $topic['slug'] === $slug);
        abort_if(! $topic, 404);

        return view('pages.safety-topic', [
            'topic' => $topic,
            'modules' => UchContent::safetyModules(),
        ]);
    }
    public function branches()
    {
        return view('pages.branches', [
            'branches' => Branch::where('is_published', true)->orderBy('sort_order')->latest()->get(),
            'branchVideos' => Video::published()->whereNotNull('related_branch_id')->ordered()->take(3)->get(),
        ]);
    }

    public function careers()
    {
        return view('pages.careers', [
            'jobs' => JobOpening::published()->orderBy('sort_order')->latest()->get(),
            'cultureImages' => UchContent::cultureImages(),
        ]);
    }

    public function equipment()
    {
        return view('pages.equipment', [
            'categories' => UchContent::equipmentCategories(),
            'categoryImages' => UchContent::equipmentCategoryImages(),
            'items' => EquipmentItem::where('is_published', true)->orderBy('sort_order')->latest()->get(),
            'equipmentVideos' => Video::published()
                ->where(function ($query): void {
                    $query->where('category', 'Vendor / Equipment')
                        ->orWhere('category', 'Product Demo')
                        ->orWhereNotNull('related_equipment_id');
                })
                ->ordered()
                ->take(3)
                ->get(),
        ]);
    }
    public function reviews()
    {
        return view('reviews.index', ['reviews' => Review::where('is_approved',true)->latest()->paginate(12)]);
    }
}
