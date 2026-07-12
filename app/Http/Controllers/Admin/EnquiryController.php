<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Enquiry::latest();
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

    public function show(Enquiry $enquiry) { return view('admin.enquiries.show', ['enquiry'=>$enquiry, 'statuses'=>Enquiry::STATUSES]); }

    public function update(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required','in:'.implode(',', Enquiry::STATUSES)],
            'assigned_to' => ['nullable','string','max:120'],
            'notes' => ['nullable','string','max:2000'],
        ]);

        $enquiry->markWorkflowTimestamps($data['status']);
        $enquiry->update($data);
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
            fputcsv($handle, ['Reference','Name','Company','Country','State/City','Email','Phone/WhatsApp','Project Location','Type of Work','Building','Preferred Contact','Urgency','Status','Created']);
            Enquiry::latest()->chunk(100, function ($enquiries) use ($handle) {
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
                        $e->display_type_of_work,
                        $e->building_type,
                        $e->preferred_contact,
                        $e->urgency,
                        $e->status,
                        $e->created_at,
                    ]);
                }
            });
            fclose($handle);
        }, 'humelix-enquiries-'.now()->format('Y-m-d').'.csv');
    }
}
