<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index() { return view('admin.team.index', ['members' => TeamMember::orderBy('sort_order')->paginate(15)]); }
    public function create() { return view('admin.team.create', ['member' => new TeamMember()]); }
    public function store(Request $request): RedirectResponse { TeamMember::create($this->validated($request)); return redirect()->route('admin.team.index')->with('success','Team member created.'); }
    public function edit(TeamMember $team) { return view('admin.team.edit', ['member'=>$team]); }
    public function update(Request $request, TeamMember $team): RedirectResponse { $team->update($this->validated($request)); return back()->with('success','Team member updated.'); }
    public function destroy(TeamMember $team): RedirectResponse { $team->delete(); return redirect()->route('admin.team.index')->with('success','Team member deleted.'); }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'=>['required','string','max:140'], 'role'=>['required','string','max:140'], 'region'=>['nullable','string','max:120'],
            'experience'=>['nullable','string','max:120'], 'certifications'=>['nullable','string','max:500'], 'bio'=>['nullable','string','max:1600'],
            'email'=>['nullable','email','max:160'], 'phone'=>['nullable','string','max:80'], 'social_url'=>['nullable','url','max:255'],
            'photo'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'], 'is_visible'=>['nullable','boolean'], 'sort_order'=>['nullable','integer','min:0','max:999'],
        ]);
        $data['is_visible'] = (bool) $request->boolean('is_visible');
        $data['sort_order'] = $data['sort_order'] ?? 10;
        if ($request->hasFile('photo')) $data['photo_path'] = $request->file('photo')->store('team','public');
        unset($data['photo']);
        return $data;
    }
}
