<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Engineer;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EngineerController extends Controller
{
    public function index()
    {
        return view('admin.engineers.index', [
            'engineers' => Engineer::withCount('assignedEnquiries')->ordered()->paginate(15),
            'statuses' => Engineer::AVAILABILITY_STATUSES,
            'assignmentContact' => SiteSetting::query()
                ->whereIn('key', $this->assignmentContactKeys())
                ->pluck('value', 'key')
                ->toArray(),
        ]);
    }

    public function create()
    {
        return view('admin.engineers.create', [
            'engineer' => new Engineer(['availability_status' => 'active', 'sort_order' => 10]),
            'users' => $this->linkableUsers(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Engineer::create($this->validated($request));

        return redirect()->route('admin.engineers.index')->with('success', 'Engineer created.');
    }

    public function edit(Engineer $engineer)
    {
        return view('admin.engineers.edit', [
            'engineer' => $engineer,
            'users' => $this->linkableUsers(),
        ]);
    }

    public function update(Request $request, Engineer $engineer): RedirectResponse
    {
        $data = $this->validated($request, $engineer);

        if ($request->boolean('remove_photo')) {
            $engineer->deleteUploadedPhoto();
            $data['photo_path'] = null;
        }

        if ($request->hasFile('photo')) {
            $engineer->deleteUploadedPhoto();
        }

        $engineer->update($data);

        return back()->with('success', 'Engineer updated.');
    }

    public function destroy(Engineer $engineer): RedirectResponse
    {
        $engineer->deleteUploadedPhoto();
        $engineer->delete();

        return redirect()->route('admin.engineers.index')->with('success', 'Engineer deleted.');
    }

    public function updateAssignmentContact(Request $request): RedirectResponse
    {
        abort_unless($request->user()?->canDeleteRecords(), 403, 'Only the Company Owner and Technical Super Admin can update assignment contact details.');

        $data = $request->validate([
            'assignment_contact_name' => ['nullable', 'string', 'max:120'],
            'assignment_contact_phone' => ['nullable', 'string', 'max:80'],
            'assignment_contact_whatsapp' => ['nullable', 'string', 'max:80'],
            'assignment_contact_email' => ['nullable', 'email', 'max:160'],
            'assignment_contact_note' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($data as $key => $value) {
            SiteSetting::setValue($key, $value, 'operations');
        }

        return back()->with('success', 'Engineer assignment contact details updated.');
    }

    private function validated(Request $request, ?Engineer $engineer = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:140'],
            'title' => ['nullable', 'string', 'max:140'],
            'field_of_work' => ['required', 'string', Rule::in(Engineer::FIELDS_OF_WORK)],
            'phone' => ['nullable', 'string', 'max:80'],
            'whatsapp' => ['nullable', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:160'],
            'region' => ['nullable', 'string', 'max:140'],
            'availability_status' => ['required', 'string', Rule::in(array_keys(Engineer::AVAILABILITY_STATUSES))],
            'notes' => ['nullable', 'string', 'max:2000'],
            'linked_user_id' => ['nullable', Rule::exists('users', 'id')],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('engineers', 'public');
        }

        $data['sort_order'] = $data['sort_order'] ?? ($engineer?->sort_order ?: 10);
        $data['linked_user_id'] = $data['linked_user_id'] ?: null;

        unset($data['photo'], $data['remove_photo']);

        return $data;
    }

    private function linkableUsers()
    {
        return User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role']);
    }

    private function assignmentContactKeys(): array
    {
        return [
            'assignment_contact_name',
            'assignment_contact_phone',
            'assignment_contact_whatsapp',
            'assignment_contact_email',
            'assignment_contact_note',
        ];
    }
}
