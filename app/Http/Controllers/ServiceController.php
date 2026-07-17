<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\PageHero;
use App\Models\Video;
use App\Support\UchContent;
use Illuminate\Support\Arr;

class ServiceController extends Controller
{
    public function index()
    {
        return view('services.index', [
            'services' => UchContent::serviceDivisions(),
            'process' => UchContent::serviceProcess(),
            'hero' => PageHero::resolve('services'),
        ]);
    }

    public function show(string $slug)
    {
        if ($target = Arr::get(UchContent::legacyServiceRedirects(), $slug)) {
            return redirect()->route('services.show', $target, 301);
        }

        $service = Arr::first(UchContent::serviceDivisions(), fn ($service) => $service['slug'] === $slug);
        abort_if(! $service, 404);

        $relatedProjects = $this->relatedProjects($service);

        return view('services.show', [
            'service' => $service,
            'services' => UchContent::serviceDivisions(),
            'relatedProjects' => $relatedProjects,
            'relatedVideos' => $this->relatedVideos($service),
        ]);
    }

    private function relatedProjects(array $service)
    {
        $terms = collect($service['project_terms'] ?? [])->filter()->values();

        if ($terms->isEmpty()) {
            return collect();
        }

        return Project::query()
            ->where('status', 'published')
            ->where(function ($query) use ($terms): void {
                foreach ($terms as $term) {
                    $query
                        ->orWhere('title', 'like', "%{$term}%")
                        ->orWhere('sector', 'like', "%{$term}%")
                        ->orWhere('system_type', 'like', "%{$term}%")
                        ->orWhere('equipment_used', 'like', "%{$term}%");
                }
            })
            ->latest()
            ->take(3)
            ->get();
    }

    private function relatedVideos(array $service)
    {
        $category = match ($service['slug']) {
            'hvac-installation' => 'HVAC',
            'solar-installation' => 'Solar',
            'electrical-maintenance' => 'Electrical',
            'vendor' => 'Vendor / Equipment',
            'home-appliance-installation' => 'Home Appliance',
            default => $service['title'],
        };

        return Video::published()
            ->where(function ($query) use ($service, $category): void {
                $query->where('related_service', $service['title'])
                    ->orWhere('category', $category);
            })
            ->ordered()
            ->take(3)
            ->get();
    }
}
