<?php
namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $service = $this->normaliseService((string) $request->input('service_needed'));

        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'phone' => ['required','string','max:60'],
            'email' => ['nullable','email','max:190'],
            'location' => ['nullable','string','max:160'],
            'building_type' => ['nullable','string','max:120'],
            'service_needed' => ['required','string','max:120'],
            'urgency' => ['nullable','string','max:80'],
            'message' => ['nullable','string','max:1200'],
        ]);

        $enquiry = Enquiry::create([
            'source' => 'chat_assistant',
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'location' => $data['location'] ?? null,
            'project_location' => $data['location'] ?? null,
            'building_type' => $data['building_type'] ?? null,
            'service_needed' => $data['service_needed'],
            'type_of_work' => $service,
            'urgency' => $data['urgency'] ?? 'This week',
            'preferred_contact' => $data['email'] ? 'Email' : 'Phone',
            'message' => $data['message'] ?? null,
            'status' => 'new',
        ]);

        return response()->json([
            'message' => "Thank you. Your request has been sent to HUMELIX SYSTEMS. Your reference number is {$enquiry->reference_number}. A team member will follow up shortly.",
            'reference_number' => $enquiry->reference_number,
        ]);
    }

    private function normaliseService(string $value): ?string
    {
        $map = [
            'HVAC' => 'HVAC',
            'Solar' => 'Solar',
            'Electrical' => 'Electrical',
            'Maintenance' => 'Maintenance',
            'Vendor' => 'Vendor',
            'Equipment' => 'Vendor',
            'Home Appliance' => 'Home Appliance',
            'Speak to Admin' => null,
            'Request Service' => null,
            'Request Quote' => null,
        ];

        return $map[$value] ?? null;
    }
}
