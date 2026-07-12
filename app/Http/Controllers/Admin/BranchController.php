<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index() { return view('admin.branches.index', ['branches' => Branch::orderBy('sort_order')->latest()->paginate(15)]); }
    public function create() { return view('admin.branches.create', ['branch' => new Branch()]); }
    public function store(Request $request): RedirectResponse { Branch::create($this->validated($request)); return redirect()->route('admin.branches.index')->with('success','Branch created.'); }
    public function edit(Branch $branch) { return view('admin.branches.edit', ['branch' => $branch]); }
    public function update(Request $request, Branch $branch): RedirectResponse { $branch->update($this->validated($request)); return back()->with('success','Branch updated.'); }
    public function destroy(Branch $branch): RedirectResponse { $branch->delete(); return redirect()->route('admin.branches.index')->with('success','Branch deleted.'); }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'=>['required','string','max:160'], 'country'=>['required','string','max:100'], 'state_city'=>['nullable','string','max:120'],
            'address'=>['nullable','string','max:190'], 'phone'=>['nullable','string','max:80'], 'email'=>['nullable','email','max:190'],
            'manager_name'=>['nullable','string','max:150'], 'service_coverage'=>['nullable','string','max:2000'],
            'status'=>['required','in:active,inactive'], 'sort_order'=>['nullable','integer','min:0'], 'is_published'=>['nullable','boolean'],
        ]);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_published'] = (bool) $request->boolean('is_published');
        return $data;
    }
}
