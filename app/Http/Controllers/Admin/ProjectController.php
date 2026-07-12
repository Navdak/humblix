<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index() { return view('admin.projects.index', ['projects' => Project::latest()->paginate(15)]); }
    public function create() { return view('admin.projects.create', ['project' => new Project()]); }
    public function store(Request $request): RedirectResponse { Project::create($this->validated($request)); return redirect()->route('admin.projects.index')->with('success','Project created.'); }
    public function edit(Project $project) { return view('admin.projects.edit', ['project'=>$project]); }
    public function update(Request $request, Project $project): RedirectResponse { $project->update($this->validated($request)); return back()->with('success','Project updated.'); }
    public function destroy(Project $project): RedirectResponse { $project->delete(); return redirect()->route('admin.projects.index')->with('success','Project deleted.'); }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title'=>['required','string','max:180'], 'slug'=>['nullable','string','max:220'], 'client_type'=>['nullable','string','max:120'],
            'country'=>['nullable','string','max:100'], 'location'=>['required','string','max:160'], 'sector'=>['required','string','max:120'],
            'service_division'=>['nullable','string','max:120'], 'system_type'=>['required','string','max:160'],
            'challenge'=>['nullable','string','max:2000'], 'solution'=>['nullable','string','max:2000'], 'result'=>['nullable','string','max:2000'],
            'equipment_used'=>['nullable','string','max:500'], 'safety_controls'=>['nullable','string','max:2000'],
            'duration'=>['nullable','string','max:120'], 'outcome'=>['nullable','string','max:2000'], 'client_testimonial'=>['nullable','string','max:2000'],
            'image'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'is_featured'=>['nullable','boolean'], 'status'=>['required','in:draft,published'],
        ]);
        $data['slug'] = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['title']);
        $data['is_featured'] = (bool) $request->boolean('is_featured');
        if ($request->hasFile('image')) $data['image_path'] = $request->file('image')->store('projects','public');
        unset($data['image']);
        return $data;
    }
}
