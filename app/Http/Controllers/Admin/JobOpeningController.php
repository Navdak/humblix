<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class JobOpeningController extends Controller
{
    public function index() { return view('admin.jobs.index', ['jobs' => JobOpening::orderBy('sort_order')->latest()->paginate(15)]); }
    public function create() { return view('admin.jobs.create', ['job' => new JobOpening()]); }
    public function store(Request $request): RedirectResponse { JobOpening::create($this->validated($request)); return redirect()->route('admin.jobs.index')->with('success','Job opening created.'); }
    public function edit(JobOpening $job) { return view('admin.jobs.edit', ['job' => $job]); }
    public function update(Request $request, JobOpening $job): RedirectResponse { $job->update($this->validated($request)); return back()->with('success','Job opening updated.'); }
    public function destroy(JobOpening $job): RedirectResponse { $job->delete(); return redirect()->route('admin.jobs.index')->with('success','Job opening deleted.'); }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title'=>['required','string','max:180'], 'department'=>['nullable','string','max:120'], 'location'=>['nullable','string','max:120'],
            'employment_type'=>['nullable','string','max:80'], 'description'=>['nullable','string','max:5000'], 'requirements'=>['nullable','string','max:5000'],
            'status'=>['required','in:draft,open,closed'], 'published_at'=>['nullable','date'], 'closing_date'=>['nullable','date'],
            'application_email'=>['nullable','email','max:190'], 'application_url'=>['nullable','url','max:255'], 'sort_order'=>['nullable','integer','min:0'],
        ]);
    }
}
