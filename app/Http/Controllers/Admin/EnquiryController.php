<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Engineer;
use App\Mail\EngineerAssignmentNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Enquiry::with('assignedEngineer')->latest();
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) => $q
                ->where('reference_number','like',"%{$s}%")
                ->orWhere('name','like',"%{$s}%")
                ->orWhere('company_name','like',"%{$s}%")
                ->orWhere('phone','like',"%{$s}%")
                ->orWhere('email','like',"%{$s}%")
                ->orWhere('country','like',"%{$s}%")
                ->orWhere('state_city','like',"%{$s}%")
                ->orWhere('location','like',"%{$s}%")
                ->orWhere('project_location','like',"%{$s}%")
                ->orWhere('service_needed','like',"%{$s}%")
                ->orWhere('type_of_work','like',"%{$s}%"));
        }
        return view('admin.enquiries.index', ['enquiries' => $query->paginate(15)->withQueryString(), 'statuses' => Enquiry::STATUSES]);
    }

    public function show(Enquiry $enquiry)
    {
        $enquiry->load('assignedEngineer');

        return view('admin.enquiries.show', [
            'enquiry' => $enquiry,
            'statuses' => Enquiry::STATUSES,
            'engineers' => Engineer::assignable()->get(),
            'canAssignEngineers' => auth()->user()?->canManage('assign_engineers') ?? false,
        ]);
    }

    public function update(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $canAssignEngineers = $request->user()?->canManage('assign_engineers') ?? false;
        $previousEngineerId = $enquiry->assigned_engineer_id;

        $data = $request->validate([
            'status' => ['required','in:'.implode(',', Enquiry::STATUSES)],
            'assigned_engineer_id' => [
                'nullable',
                Rule::exists('engineers', 'id')->whereNull('deleted_at'),
            ],
            'send_assignment_alert' => ['nullable','boolean'],
            'confirmed_site_address' => ['nullable','string','max:500'],
            'notes' => ['nullable','string','max:2000'],
        ]);

        if (! $canAssignEngineers) {
            unset($data['assigned_engineer_id'], $data['send_assignment_alert']);
        }

        $enquiry->markWorkflowTimestamps($data['status']);
        unset($data['send_assignment_alert']);

        $enquiry->update($data);

        if ($canAssignEngineers && array_key_exists('assigned_engineer_id', $data)) {
            $enquiry->load('assignedEngineer');

            if ($enquiry->assignedEngineer) {
                $enquiry->forceFill(['assigned_to' => $enquiry->assignedEngineer->name])->save();
            } elseif ($previousEngineerId && ! $enquiry->assigned_engineer_id) {
                $enquiry->forceFill(['assigned_to' => null])->save();
            }

            $shouldAlert = $request->boolean('send_assignment_alert')
                && $enquiry->assigned_engineer_id
                && (int) $previousEngineerId !== (int) $enquiry->assigned_engineer_id
                && filled($enquiry->assignedEngineer?->email);

            if ($shouldAlert) {
                try {
                    Mail::to($enquiry->assignedEngineer->email)->send(new EngineerAssignmentNotification($enquiry, $enquiry->assignedEngineer));
                } catch (\Throwable $exception) {
                    Log::warning('Engineer assignment email failed.', [
                        'enquiry_id' => $enquiry->id,
                        'engineer_id' => $enquiry->assigned_engineer_id,
                        'message' => $exception->getMessage(),
                    ]);

                    return back()->with('success', 'Enquiry updated. Email alert could not be sent, so please contact the engineer manually.');
                }
            }
        }

        return back()->with('success','Enquiry updated.');
    }

    public function destroy(Enquiry $enquiry): RedirectResponse
    {
        $enquiry->delete();
        return redirect()->route('admin.enquiries.index')->with('success','Enquiry deleted.');
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Reference','Name','Company','Country','State/City','Email','Phone/WhatsApp','Project Location','Site Address','Type of Work','Building','Preferred Contact','Urgency','Assigned Engineer','Status','Created']);
            Enquiry::with('assignedEngineer')->latest()->chunk(100, function ($enquiries) use ($handle) {
                foreach ($enquiries as $e) {
                    fputcsv($handle, [
                        $e->reference_number,
                        $e->name,
                        $e->company_name,
                        $e->country,
                        $e->state_city,
                        $e->email,
                        $e->phone,
                        $e->display_location,
                        $e->display_site_address,
                        $e->display_type_of_work,
                        $e->building_type,
                        $e->preferred_contact,
                        $e->urgency,
                        $e->assignedEngineerLabel(),
                        $e->status,
                        $e->created_at,
                    ]);
                }
            });
            fclose($handle);
        }, 'humelix-enquiries-'.now()->format('Y-m-d').'.csv');
    }
}
