<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ClientJobMessageNotification;
use App\Models\AdminNotification;
use App\Models\AdminNotificationRead;
use App\Models\ClientJob;
use App\Models\Enquiry;
use App\Models\JobMessage;
use App\Models\JobMessageAttachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ClientJobController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientJob::query()
            ->with(['enquiry', 'assignedEngineer', 'latestMessage'])
            ->latest('updated_at');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = (string) $request->input('search');
            $query->where(function ($query) use ($search): void {
                $query->where('job_reference', 'like', "%{$search}%")
                    ->orWhereHas('enquiry', fn ($query) => $query
                        ->where('reference_number', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('type_of_work', 'like', "%{$search}%")
                        ->orWhere('service_needed', 'like', "%{$search}%"));
            });
        }

        return view('admin.client-jobs.index', [
            'clientJobs' => $query->paginate(15)->withQueryString(),
            'statuses' => ClientJob::STATUSES,
        ]);
    }

    public function show(ClientJob $clientJob)
    {
        $clientJob->load(['enquiry.assignedEngineer', 'assignedEngineer', 'messages.user', 'messages.attachments']);
        $this->markAdminThreadRead($clientJob);
        $this->markJobNotificationsRead($clientJob);

        return view('admin.client-jobs.show', [
            'clientJob' => $clientJob,
            'statuses' => ClientJob::STATUSES,
            'paymentStatuses' => ClientJob::PAYMENT_STATUSES,
            'canManageCommercialAgreement' => auth()->user()?->canManage('commercial_agreements') ?? false,
        ]);
    }

    public function storeFromEnquiry(Request $request, Enquiry $enquiry): RedirectResponse
    {
        abort_unless($request->user()?->canManage('client_jobs'), 403);

        $enquiry->load('clientJob');

        if ($enquiry->clientJob) {
            return redirect()->route('admin.client-jobs.show', $enquiry->clientJob)
                ->with('success', 'Client Job Portal already exists for this enquiry.');
        }

        if (blank($enquiry->email) && blank($enquiry->phone)) {
            return back()->withErrors([
                'client_job' => 'Add client email or phone/WhatsApp before creating a private job portal.',
            ]);
        }

        $job = ClientJob::create([
            'enquiry_id' => $enquiry->id,
            'assigned_engineer_id' => $enquiry->assigned_engineer_id,
            'status' => $enquiry->assigned_engineer_id ? 'engineer_assigned' : 'confirmed',
            'portal_enabled' => true,
            'created_by' => $request->user()?->id,
            'updated_by' => $request->user()?->id,
        ]);

        $job->messages()->create([
            'sender_type' => 'system',
            'sender_name' => 'HUMELIX System',
            'body' => 'Client Job Portal created from enquiry '.$enquiry->reference_number.'.',
            'visibility' => 'internal',
            'read_by_admin_at' => now(),
        ]);

        return redirect()->route('admin.client-jobs.show', $job)
            ->with('success', 'Client Job Portal created. You can copy or email the private client link from here.');
    }

    public function update(Request $request, ClientJob $clientJob): RedirectResponse
    {
        abort_unless($request->user()?->canManage('client_jobs'), 403);

        $rules = [
            'status' => ['required', Rule::in(ClientJob::STATUSES)],
            'portal_enabled' => ['nullable', 'boolean'],
        ];

        if ($request->user()?->canManage('commercial_agreements')) {
            $rules += [
                'agreed_amount' => ['nullable', 'numeric', 'min:0', 'max:999999999999.99'],
                'currency' => ['required_with:agreed_amount', 'nullable', 'string', 'max:10'],
                'payment_status' => ['required', Rule::in(ClientJob::PAYMENT_STATUSES)],
                'agreement_note' => ['nullable', 'string', 'max:3000'],
                'agreed_at' => ['nullable', 'date'],
            ];
        }

        $data = $request->validate($rules);

        $update = [
            'status' => $data['status'],
            'portal_enabled' => $request->boolean('portal_enabled'),
            'updated_by' => $request->user()?->id,
        ];

        if ($request->user()?->canManage('commercial_agreements')) {
            $update += [
                'agreed_amount' => $data['agreed_amount'] ?? null,
                'currency' => $data['currency'] ?: 'NGN',
                'payment_status' => $data['payment_status'],
                'agreement_note' => $data['agreement_note'] ?? null,
                'agreed_at' => $data['agreed_at'] ?? null,
            ];
        }

        $clientJob->update($update);

        return back()->with('success', 'Client job updated.');
    }

    public function storeMessage(Request $request, ClientJob $clientJob): RedirectResponse
    {
        abort_unless($request->user()?->canManage('client_jobs'), 403);

        $data = $request->validate([
            'body' => ['nullable', 'string', 'max:5000'],
            'visibility' => ['required', Rule::in(JobMessage::VISIBILITIES)],
            'send_client_email' => ['nullable', 'boolean'],
            'attachments' => ['nullable', 'array', 'max:3'],
            'attachments.*' => ['file', 'max:10240', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx'],
        ]);

        $attachments = $request->file('attachments', []);
        $this->ensureMessageHasContent($data['body'] ?? null, $attachments);

        $message = $clientJob->messages()->create([
            'sender_type' => 'admin',
            'user_id' => $request->user()?->id,
            'sender_name' => $request->user()?->displayName(),
            'body' => $this->messageBody($data['body'] ?? null, $attachments, $data['visibility']),
            'visibility' => $data['visibility'],
            'read_by_admin_at' => now(),
        ]);

        $this->storeAttachments($clientJob, $message, $attachments, 'admin');

        $clientJob->forceFill([
            'last_admin_message_at' => now(),
            'client_unread_count' => $data['visibility'] === 'client' ? $clientJob->client_unread_count + 1 : $clientJob->client_unread_count,
            'updated_by' => $request->user()?->id,
        ])->save();

        if ($data['visibility'] === 'client' && $request->boolean('send_client_email') && filled($clientJob->enquiry?->email)) {
            try {
                Mail::to($clientJob->enquiry->email)->send(new ClientJobMessageNotification($clientJob->fresh(['enquiry']), $message->fresh('attachments')));
            } catch (\Throwable $exception) {
                Log::warning('Client job message email failed.', [
                    'client_job_id' => $clientJob->id,
                    'message_id' => $message->id,
                    'error' => $exception->getMessage(),
                ]);

                return back()->with('success', 'Message saved. Email could not be sent, so contact the client manually if urgent.');
            }
        }

        return back()->with('success', $data['visibility'] === 'internal' ? 'Internal note saved.' : 'Client message saved.');
    }

    public function regenerateToken(Request $request, ClientJob $clientJob): RedirectResponse
    {
        abort_unless($request->user()?->canManage('client_jobs'), 403);

        $clientJob->update([
            'portal_token' => ClientJob::generatePortalToken(),
            'portal_enabled' => true,
            'updated_by' => $request->user()?->id,
        ]);

        $clientJob->messages()->create([
            'sender_type' => 'system',
            'sender_name' => 'HUMELIX System',
            'body' => 'Client portal link was regenerated by '.$request->user()?->displayName().'.',
            'visibility' => 'internal',
            'read_by_admin_at' => now(),
        ]);

        return back()->with('success', 'Client portal link regenerated.');
    }

    public function messages(Request $request, ClientJob $clientJob): JsonResponse
    {
        abort_unless($request->user()?->canManage('client_jobs'), 403);

        $after = max(0, (int) $request->query('after', 0));
        $messages = $clientJob->messages()
            ->where('id', '>', $after)
            ->limit(50)
            ->get()
            ->load('attachments');

        $this->markAdminThreadRead($clientJob);

        return response()->json([
            'messages' => $messages->map(fn (JobMessage $message) => $this->messagePayload($message))->values(),
        ]);
    }

    private function markAdminThreadRead(ClientJob $clientJob): void
    {
        if ($clientJob->admin_unread_count > 0) {
            $clientJob->forceFill(['admin_unread_count' => 0])->save();
        }

        $clientJob->messages()
            ->whereNull('read_by_admin_at')
            ->update(['read_by_admin_at' => now()]);
    }

    private function markJobNotificationsRead(ClientJob $clientJob): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        AdminNotification::query()
            ->where('type', 'client_job_message')
            ->where('action_url', route('admin.client-jobs.show', $clientJob, false))
            ->pluck('id')
            ->each(fn (int $notificationId) => AdminNotificationRead::updateOrCreate(
                ['admin_notification_id' => $notificationId, 'user_id' => $user->id],
                ['read_at' => now()],
            ));
    }

    private function messagePayload(JobMessage $message): array
    {
        return [
            'id' => $message->id,
            'sender_type' => $message->sender_type,
            'sender_name' => $message->senderLabel(),
            'body' => $message->body,
            'visibility' => $message->visibility,
            'created_at' => $message->created_at?->toIso8601String(),
            'human_time' => $message->created_at?->diffForHumans(),
            'attachments' => $message->attachments->map(fn (JobMessageAttachment $attachment) => [
                'id' => $attachment->id,
                'name' => $attachment->original_name,
                'mime_type' => $attachment->mime_type,
                'is_image' => $attachment->isImage(),
                'size' => $attachment->formattedSize(),
                'url' => route('admin.client-jobs.attachments.show', [$message->client_job_id, $attachment], false),
            ])->values(),
        ];
    }

    public function attachment(ClientJob $clientJob, JobMessageAttachment $attachment)
    {
        abort_unless(auth()->user()?->canManage('client_jobs'), 403);
        abort_unless((int) $attachment->client_job_id === (int) $clientJob->id, 404);
        abort_unless(Storage::disk($attachment->disk)->exists($attachment->file_path), 404);

        return Storage::disk($attachment->disk)->response($attachment->file_path, $attachment->original_name, [
            'Content-Type' => $attachment->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => ($attachment->isImage() ? 'inline' : 'attachment').'; filename="'.addslashes($attachment->original_name).'"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function ensureMessageHasContent(?string $body, array $attachments): void
    {
        if (filled($body) || count($attachments) > 0) {
            return;
        }

        throw ValidationException::withMessages([
            'body' => 'Write a message or attach at least one file.',
        ]);
    }

    private function messageBody(?string $body, array $attachments, string $visibility): string
    {
        if (filled($body)) {
            return trim((string) $body);
        }

        return $visibility === 'internal' ? 'Internal attachment saved.' : 'Attachment sent.';
    }

    private function storeAttachments(ClientJob $clientJob, JobMessage $message, array $attachments, string $senderType): void
    {
        foreach ($attachments as $file) {
            $path = $file->store('client-job-attachments/'.$clientJob->id, 'local');

            $message->attachments()->create([
                'client_job_id' => $clientJob->id,
                'sender_type' => $senderType,
                'disk' => 'local',
                'file_path' => $path,
                'original_name' => Str::limit($file->getClientOriginalName(), 180, ''),
                'mime_type' => $file->getMimeType(),
                'size_bytes' => $file->getSize() ?: 0,
            ]);
        }
    }
}
