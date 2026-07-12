<?php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Video;
use App\Support\UchContent;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::where('status','published');
        if ($request->filled('service')) $query->where('service_division', $request->service);
        if ($request->filled('country')) $query->where('country', $request->country);

        return view('projects.index', [
            'projects' => $query->latest()->paginate(12)->withQueryString(),
            'serviceDivisions' => Project::where('status','published')->whereNotNull('service_division')->distinct()->pluck('service_division')->filter()->values(),
            'countries' => Project::where('status','published')->whereNotNull('country')->distinct()->pluck('country')->filter()->values(),
            'projectFallbackImages' => UchContent::projectFallbackImages(),
        ]);
    }

    public function show(Project $project)
    {
        abort_if($project->status !== 'published', 404);
        return view('projects.show', [
            'project' => $project,
            'projectFallbackImages' => UchContent::projectFallbackImages(),
            'relatedVideos' => Video::published()->where('related_project_id', $project->id)->ordered()->take(4)->get(),
        ]);
    }
}
