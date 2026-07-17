<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\PageHero;
use App\Models\Project;
use App\Models\Review;
use App\Models\Video;
use App\Support\UchContent;

class HomeController extends Controller
{
    public function __invoke()
    {
        return view('home', [
            'services' => collect(UchContent::services())->take(6),
            'sectors' => collect(UchContent::sectors())->take(8),
            'trustBadges' => UchContent::trustBadges(),
            'whyChoose' => UchContent::whyChoose(),
            'projectFallbackImages' => UchContent::projectFallbackImages(),
            'cultureImages' => UchContent::cultureImages(),
            'projects' => Project::where('status','published')->where('is_featured',true)->latest()->take(4)->get(),
            'reviews' => Review::where('is_approved',true)->latest()->take(5)->get(),
            'articles' => Article::published()->latest('published_at')->take(3)->get(),
            'featuredVideos' => Video::published()->featured()->ordered()->take(4)->get(),
            'hero' => PageHero::resolve('home', [
                'eyebrow' => 'HUMELIX LIMITED',
                'title' => config('app.name', 'HUMELIX LIMITED'),
                'subtitle' => '',
                'fallback_image_path' => 'images/generated/home/home-hero-engineering.jpg',
            ]),
        ]);
    }
}
