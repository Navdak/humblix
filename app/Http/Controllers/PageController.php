<?php
namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\EquipmentItem;
use App\Models\JobOpening;
use App\Models\PageHero;
use App\Models\Project;
use App\Models\Review;
use App\Models\SafetyTopic;
use App\Models\TeamMember;
use App\Models\Video;
use App\Support\UchContent;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;

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

    public function teamMember(TeamMember $teamMember)
    {
        abort_unless($teamMember->is_visible, 404);

        return view('team.show', [
            'member' => $teamMember,
            'relatedMembers' => TeamMember::where('is_visible', true)
                ->whereKeyNot($teamMember->getKey())
                ->orderBy('sort_order')
                ->take(4)
                ->get(),
        ]);
    }
    public function safety()
    {
        $topics = Schema::hasTable('safety_topics') ? SafetyTopic::published()->ordered()->get() : collect();

        return view('pages.safety', [
            'hero' => PageHero::resolve('safety'),
            'pillars' => UchContent::safetyPillars(),
            'modules' => $topics->isNotEmpty()
                ? $topics->map(fn (SafetyTopic $topic) => $topic->toPublicArray())->all()
                : UchContent::safetyModules(),
            'process' => UchContent::safetyProcess(),
            'divisionSafety' => UchContent::safetyAcrossDivisions(),
            'safetyVideos' => Video::published()->where('category', 'Safety')->ordered()->take(3)->get(),
        ]);
    }

    public function safetyTopic(string $slug)
    {
        $topicRecord = Schema::hasTable('safety_topics') ? SafetyTopic::published()->where('slug', $slug)->first() : null;
        $topics = Schema::hasTable('safety_topics') ? SafetyTopic::published()->ordered()->get() : collect();
        $modules = $topics->isNotEmpty()
            ? $topics->map(fn (SafetyTopic $topic) => $topic->toPublicArray())->all()
            : UchContent::safetyModules();
        $topic = $topicRecord?->toPublicArray()
            ?: Arr::first(UchContent::safetyModules(), fn ($topic) => $topic['slug'] === $slug);

        abort_if(! $topic, 404);

        return view('pages.safety-topic', [
            'topic' => $topic,
            'topicRecord' => $topicRecord,
            'modules' => $modules,
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
        $approvedReviews = Review::where('is_approved', true);
        $reviewCount = (clone $approvedReviews)->count();
        $averageRating = $reviewCount > 0 ? round((float) (clone $approvedReviews)->avg('rating'), 1) : null;
        $reviews = (clone $approvedReviews)->latest()->paginate(12);

        return view('reviews.index', [
            'reviews' => $reviews,
            'structuredData' => array_values(array_filter([
                $reviewCount > 0 ? [
                    '@context' => 'https://schema.org',
                    '@type' => 'ItemList',
                    'name' => 'HUMELIX LIMITED client reviews',
                    'itemListElement' => $reviews->getCollection()->values()->map(fn (Review $review, int $index) => [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'item' => [
                            '@type' => 'Review',
                            'author' => ['@type' => 'Person', 'name' => $review->client_name],
                            'reviewBody' => $review->comment,
                            'reviewRating' => [
                                '@type' => 'Rating',
                                'ratingValue' => $review->rating,
                                'bestRating' => 5,
                                'worstRating' => 1,
                            ],
                            'itemReviewed' => ['@type' => 'Organization', 'name' => 'HUMELIX LIMITED'],
                        ],
                    ])->all(),
                ] : null,
                $reviewCount > 0 ? [
                    '@context' => 'https://schema.org',
                    '@type' => 'AggregateRating',
                    'itemReviewed' => ['@type' => 'Organization', 'name' => 'HUMELIX LIMITED'],
                    'ratingValue' => $averageRating,
                    'reviewCount' => $reviewCount,
                    'bestRating' => 5,
                    'worstRating' => 1,
                ] : null,
            ])),
        ]);
    }
}
