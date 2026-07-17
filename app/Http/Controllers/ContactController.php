<?php
namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\AdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ContactController extends Controller
{
    public function create()
    {
        $prefill = request('type_of_work') ?? request('service');

        return view('contact', [
            'typeOfWorkOptions' => Enquiry::TYPE_OF_WORK_OPTIONS,
            'buildingTypeOptions' => Enquiry::BUILDING_TYPE_OPTIONS,
            'preferredContactOptions' => Enquiry::PREFERRED_CONTACT_OPTIONS,
            'prefilledTypeOfWork' => $this->normaliseTypeOfWork((string) $prefill),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->filled('type_of_work')) {
            $request->merge(['type_of_work' => $this->normaliseTypeOfWork((string) $request->input('type_of_work'))]);
        }

        $data = $request->validate([
            'full_name' => ['required','string','max:150'],
            'company_name' => ['nullable','string','max:190'],
            'country' => ['required','string','max:100'],
            'state_city' => ['required','string','max:120'],
            'email' => ['required','email','max:190'],
            'phone_whatsapp' => ['required','string','max:60'],
            'project_location' => ['required','string','max:190'],
            'type_of_work' => ['required','string','in:'.implode(',', Enquiry::TYPE_OF_WORK_OPTIONS)],
            'building_type' => ['required','string','in:'.implode(',', Enquiry::BUILDING_TYPE_OPTIONS)],
            'brief_description' => ['required','string','max:5000'],
            'preferred_contact' => ['required','string','in:'.implode(',', Enquiry::PREFERRED_CONTACT_OPTIONS)],
            'urgency' => ['nullable','string','max:80'],
            'uploaded_files' => ['nullable','array','max:5'],
            'uploaded_files.*' => ['file','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        $uploads = $this->storeUploads($request->file('uploaded_files', []));

        $enquiry = Enquiry::create([
            'source' => 'contact_form',
            'name' => $data['full_name'],
            'company_name' => $data['company_name'] ?? null,
            'country' => $data['country'],
            'state_city' => $data['state_city'],
            'phone' => $data['phone_whatsapp'],
            'email' => $data['email'],
            'location' => $data['project_location'],
            'project_location' => $data['project_location'],
            'building_type' => $data['building_type'],
            'service_needed' => $data['type_of_work'],
            'type_of_work' => $data['type_of_work'],
            'urgency' => $data['urgency'] ?? null,
            'preferred_contact' => $data['preferred_contact'],
            'message' => $data['brief_description'],
            'attachment_path' => $uploads[0]['path'] ?? null,
            'uploaded_files' => $uploads ?: null,
            'status' => 'new',
        ]);
        AdminNotification::createForEnquiry($enquiry);

        return back()
            ->with('success', "Thank you. Your request has been received. Your reference number is {$enquiry->reference_number}. A Humelix representative will contact you shortly.")
            ->with('reference_number', $enquiry->reference_number);
    }

    private function storeUploads(array|UploadedFile|null $files): array
    {
        $files = is_array($files) ? $files : array_filter([$files]);

        return collect($files)
            ->filter(fn ($file) => $file instanceof UploadedFile)
            ->take(5)
            ->map(fn (UploadedFile $file) => [
                'path' => $file->store('enquiries', 'public'),
                'original_name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ])
            ->values()
            ->all();
    }

    private function normaliseTypeOfWork(string $value): ?string
    {
        $value = trim($value);

        $map = [
            'hvac-installation' => 'HVAC',
            'hvac' => 'HVAC',
            'solar-installation' => 'Solar',
            'solar' => 'Solar',
            'electrical-maintenance' => 'Electrical',
            'electrical' => 'Electrical',
            'maintenance' => 'Maintenance',
            'vendor' => 'Vendor',
            'equipment' => 'Vendor',
            'home-appliance-installation' => 'Home Appliance',
            'home appliance' => 'Home Appliance',
            'home-appliance' => 'Home Appliance',
        ];

        return $map[strtolower($value)] ?? (in_array($value, Enquiry::TYPE_OF_WORK_OPTIONS, true) ? $value : null);
    }
}
